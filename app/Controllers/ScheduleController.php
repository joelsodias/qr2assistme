<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use App\Entities\ScheduleEntity;

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
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
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
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }


    public function getIdentifiedAttendeeScheduler()
    {

        $section = "attendee";
        $provider = $this->getSessionLoginInfo("provider");
        $user = $this->getSessionLoginInfo("user", $section);

        if ($provider && $this->checkSessionLoggedOn($section, $provider)) {
            $data = [
                "page_title" => "Agendamento",
                "layout" => "layouts/layout_bootstrap_clear_noresize",
                "user_name" => $user->user_name ?? null,
            ];

            return $this->view("content/schedule_attendee_view", $data);
        } else {
            return redirect()->to("/attendee/login/schedule");
        }
    }

    public function postIdentifiedAttendeeScheduler()
    {

        $section = "attendee";
        $provider = $this->getSessionLoginInfo("provider");

        if ($provider && $this->checkSessionLoggedOn($section, $provider)) {
            $scheduleDate = $this->getRequestParam("schedule");
            $scheduleService = $this->getRequestParam("schedule_service");
            $schedulePhone = $this->getRequestParam("schedule_phone");
            $scheduleDescription = $this->getRequestParam("schedule_description");
            $scheduleContact = $this->getRequestParam("schedule_contact");

            $user = $this->getSessionLoginInfo("user", $section, $provider);
            $token = $this->getSessionLoginInfo("token", $section, $provider);

            $model = new \App\Models\ChatUserModel();
            $builder = $model->builder();

            $builder->where($provider . "_email", $user["email"]);
            $u = $builder->get(1)->getResult("\App\Entities\ChatUserEntity");

            $scheduleModel = new \App\Models\ScheduleModel();
            $s = new \App\Entities\ScheduleEntity();

            $s->chat_user_uid = $u[0]->chat_user_uid ?? null;
            $s->schedule_uid = $this->getNewUUidString();
            $s->object_uid = $_SESSION["QR"]["object"]->object_uid ?? null;
            $s->customer_uid = $_SESSION["QR"]["object"]->customer_uid ?? null;

            $s->schedule_object_name = $_SESSION["QR"]["object"]->object_name ?? null;
            $s->schedule_service_name = $scheduleService ?? null;
            $s->schedule_contact_name = $scheduleContact ?? null;
            $s->schedule_contact_phone = $schedulePhone ?? null;
            $s->schedule_description = $scheduleDescription ?? null;
            $s->schedule_status = 'requested';
            $s->schedule_date = str_replace("-T", " 13:00", str_replace("-M", " 09:00", $scheduleDate));

            $r = $scheduleModel->insert($s);

            return redirect()->to("/attendee/schedule/show/" . $s->schedule_uid);
        } else {
            return redirect()->to("/attendee/login/schedule");
        }
    }

    public function showIdentifiedAttendeeSchedule($schedule_uid)
    {

        $scheduleModel = new \App\Models\ScheduleModel();

        $builder = $scheduleModel->builder();

        $builder->where("schedule_uid", $schedule_uid);
        $r =  $builder->get(1)->getResult("\App\Entities\ScheduleEntity");

        if ($r && is_array($r) && count($r) > 0) {
            $s = $r[0];

            $scheduleDate = $s->schedule_date;
            $scheduleService = $s->schedule_service_name;
            $scheduleContact = $s->schedule_contact_name;
            $scheduleDescription = $s->schedule_description;
            $schedulePhone = $s->schedule_contact_phone;

            $data = [
                "page_title" => "Agendamento",
                "layout" => "layouts/layout_bootstrap_clear_noresize",
                "schedule" => $s,
                "scheduleDate" =>  $scheduleDate,
                "scheduleService" => $scheduleService,
                "schedulePhone" => $schedulePhone,
                "scheduleDescription" => $scheduleDescription,
                "scheduleContact" => $scheduleContact,
            ];

            return $this->view("content/schedule_attendee_success_view", $data);
        }
    }

    public function finishAttendeeProcess()
    {
        $data = [
            "page_title" => "Agendamento",
            "layout" => "layouts/layout_bootstrap_clear_noresize",
        ];
        return $this->view("content/schedule_attendee_finish_view", $data);
    }
}
