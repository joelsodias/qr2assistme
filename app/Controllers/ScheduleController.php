<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;

use function PHPUnit\Framework\directoryExists;

class ScheduleController extends BaseAdminLteController
{

	public function openSchedule($schedule_uid)
	{
		$scheduleModel = new \App\Models\ScheduleModel();

		$schedule = $scheduleModel->getScheduleComplete($schedule_uid);

		if (isset($schedule)) {

			$data = [
				"page_title" => "Atendimento",
				"enable_content_header" => true,
				"enable_top_navbar" => false,
				"enable_sidebar" => false,
				"content_header_title" => "Atender Agendamento",
				"before_sidebar" => "",
				"sidebar" => "",
				"enable_datatables" => false,
				"schedule" => $schedule,
			];

			return $this->view("content/admin/field/field_admin_schedule_view", $data);
		} else throw PageNotFoundException::forPageNotFound();
	}

	public function saveSchedule()
	{
		$data = [];
		$record = [];
		$errors = [];

		$schedule_uid = $this->getRequestParam("schedule_uid");
		$object_uid = $this->getRequestParam("object_uid");

		if ($this->validateUuid($schedule_uid, false) && $this->validateUuid($object_uid, false)) {
			$scheduleModel = new \App\Models\ScheduleModel();
			$builder = $scheduleModel->builder();
			$builder->where("schedule_uid", $schedule_uid);
			$result = $builder->get(1)->getCustomResultObject("\App\Entities\ScheduleEntity");

			$s = $result[0];

			$s->object_uid = $this->getRequestParam("object_uid") ?? $s->object_uid;
			$s->schedule_description = $this->getRequestParam("schedule_description") ?? $s->schedule_description;

			$now = new Time("now");
			$todayFolder = $now->toDateString();

			$s->schedule_ended_at = $now;

			$scheduleModel->save($s);

			$files = $this->getRequestFiles();

			if (isset($files["image"])) {

				foreach ($files["image"] as $file) {

					$fileModel = new \App\Models\FileModel();
					$f = new \App\Entities\FileEntity();

					$f->file_uid = $this->getNewUUidString(); 
					$f->worker_uid = $s->worker_uid;
					$f->schedule_uid = $s->schedule_uid;
					$f->object_uid = $s->object_uid;
					$f->customer_uid = $s->customer_uid;
					$f->file_context = "object";
					$f->file_description = $s->ended_at . ": object added during a schedule";

					$fileName = "file_" . $file->getRandomName();

					$f->file_name = $fileName;
					$f->file_folder = $todayFolder;

					$ext = $file->getClientExtension();

					if ($file->isValid() && !$file->hasMoved()) {
						if (!directoryExists(ROOTPATH . 'public/images/uploads/' . $todayFolder)) {
							mkdir(ROOTPATH . 'public/images/uploads/' . $todayFolder);
						}
						$file->move(ROOTPATH . 'public/images/uploads/' . $todayFolder, $fileName);
						log_message("info", $fileName . " saved to public upload folder");
					}

					$fileModel->save($f);

					unset($f);
					unset($fileModel);
				}
			}
		} else throw PageNotFoundException::forPageNotFound();
	}
}
