<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\I18n\Time;
use App\Entities\ScheduleEntity;
use DateTime;

use function PHPUnit\Framework\directoryExists;

class ScheduleController extends BaseAdminLteController
{

    public function openSchedule($schedule_uid)
    {
        $scheduleModel = new \App\Models\ScheduleModel();

        $schedule = $scheduleModel->getScheduleComplete($schedule_uid);

        if (isset($schedule)) {
            $fileModel = new \App\Models\FileModel();

            $files = $fileModel->getFiles($schedule_uid);

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
                "files" => $files,
            ];

            return $this->view("content/admin/field/field_admin_schedule_view", $data);
        } else {
            throw PageNotFoundException::forPageNotFound();
        }
    }


    private function gdResizeImage($file, $w, $h, $crop = false)
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public function saveSchedule()
    {
        $data = [];
        $record = [];
        $errors = [];

        $schedule_uid = $this->getRequestParam("schedule_uid");
        $object_uid = $this->getRequestParam("object_uid");
        $saveAction = $this->getRequestParam("post_save_action");


        if ($this->validateUuid($schedule_uid, false) && $this->validateUuid($object_uid, false)) {
            $scheduleModel = new \App\Models\ScheduleModel();
            $builder = $scheduleModel->builder();
            $builder->where("schedule_uid", $schedule_uid);
            $result = $builder->get(1)->getCustomResultObject("\App\Entities\ScheduleEntity");

            $s = $result[0];

            $s->object_uid = $object_uid ?? $s->object_uid;
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
                        $savePath = ROOTPATH . 'public/images/uploads/' . $todayFolder;
                        if (!is_dir($savePath)) {
                            mkdir($savePath, 0755, true);
                        }
                        $file->move($savePath, $fileName);
                        $dst = $this->gdResizeImage($savePath . '/' . $fileName, 1024, 768);
                        imagejpeg($dst, $savePath . '/' . $fileName);
                        log_message("info", $fileName . " saved to public upload folder");
                    }

                    $fileModel->save($f);

                    unset($f);
                    unset($fileModel);
                }
            }

            if ($saveAction == "continue") {
                return redirect()->to("/field/schedule/" . $schedule_uid);
            } else {
                return redirect()->to("/field");
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


    public function showAdminScheduleDate($date)
    {

        $d = new DateTime($date);

        $scheduleModel = new \App\Models\ScheduleModel();
        $workerModel = new \App\Models\WorkerModel();

        $schedules = $scheduleModel->getScheduleCompleteByDate($d->format("Y-m-d"));

        $workers = $workerModel->getWorker(null,"field");

        $data = [
            "page_title" => "Agenda do dia: " . $d->format("d/m/Y"),
            "schedules" => $schedules,
            "workers" => $workers,
            "current_date" => $d,
            "enable_datatables" => true,
            "list_url" => "/admin/api/schedule/date/" . $d->format("Y-m-d"),
        ];
        return $this->view("content/admin/admin_schedule_date_view", $data);
    }

    public function confirmSchedule(){
        $schedule_uid = $this->getRequestParam("schedule_uid");
        $schedule_date = $this->getRequestParam("schedule_date");
        $schedule_current_date = $this->getRequestParam("schedule_current_date");
        $schedule_time = $this->getRequestParam("schedule_time");
        $worker_uid = $this->getRequestParam("worker_uid");

        $scheduleModel = new \App\Models\ScheduleModel();
        $s = $scheduleModel->getSchedule($schedule_uid);
        //$s = new \App\Entities\ScheduleEntity();
        $s->schedule_uid = $schedule_uid;
        $d = DateTime::createFromFormat("d/m/Y",$schedule_date);
        $s->schedule_date = $d->format("Y-m-d") . ' ' . $schedule_time;
        $s->worker_uid = $worker_uid;
        $s->schedule_status = "scheduled";

        $scheduleModel->save($s);
        
        return redirect()->to("/admin/schedule/date/$schedule_current_date");
    }
    
    public function changeSchedule(){
        $schedule_uid = $this->getRequestParam("schedule_uid");
        $schedule_date = $this->getRequestParam("schedule_date");
        $schedule_current_date = $this->getRequestParam("schedule_current_date");
        $schedule_time = $this->getRequestParam("schedule_time");
        $worker_uid = $this->getRequestParam("worker_uid");

        $scheduleModel = new \App\Models\ScheduleModel();
        $s = $scheduleModel->getSchedule($schedule_uid);
        //$s = new \App\Entities\ScheduleEntity();
        $s->schedule_uid = $schedule_uid;
        $d = DateTime::createFromFormat("d/m/Y",$schedule_date);
        $s->schedule_date = $d->format("Y-m-d") . ' ' . $schedule_time;
        $s->worker_uid = $worker_uid;

        $scheduleModel->save($s);
        
        return redirect()->to("/admin/schedule/date/$schedule_current_date");
    }
    
    public function cancelSchedule(){
        $schedule_uid = $this->getRequestParam("schedule_uid");
        $schedule_current_date = $this->getRequestParam("schedule_current_date");

        $scheduleModel = new \App\Models\ScheduleModel();
        $s = $scheduleModel->getSchedule($schedule_uid);
        $s->schedule_status = "canceled";

        $scheduleModel->save($s);
        
        return redirect()->to("/admin/schedule/date/$schedule_current_date");
    }



    public function getList($date)
    {


        $get = $this->getRequest()->getGet();
        $post = $this->getRequest()->getPost();
        $body = $this->getRequest()->getBody();
        $json = $this->getRequest()->getJSON();

        $d = new DateTime($date);

        $model = new \App\Models\ScheduleModel();

        $records = $model->getScheduleCompleteByDate($d->format("Y-m-d"));

        $countAll = 0;
        $countFiltered = 0;
        $result = [];
        if ($records) {
            $countAll = count($records);
            $countFiltered = count($records);

            foreach ($records as $key => $value) {
                $result[] =  [
                    "schedule_uid" => $value->schedule_uid,
                    "schedule_date" => $value->schedule_date,
                    "schedule_status" => $value->schedule_status,
                    "object_uid" => $value->object_uid,
                    "schedule_object_name" => $value->schedule_object_name,
                    "schedule_service_name" => $value->schedule_service_name,
                    "customer_uid" => $value->customer_uid,
                    "customer_name" => $value->customer_name,
                    "customer_email" => $value->customer_email,
                    "schedule_contact_name" => $value->schedule_contact_name,
                    "schedule_contact_phone" => $value->schedule_contact_phone,
                    "schedule_address" => str_replace("\n", " ", $value->schedule_address),
                    "schedule_city" => $value->schedule_city,
                    "worker_uid" => $value->worker_uid,
                    "worker_name" => $value->worker_name,
                    "worker_avatar" => $value->worker_avatar,
                ];
                unset($value->id);
                unset($value->deleted_at);
            }
        }
        $data = [
            "status_code" => 200,
            "status" => "Success",
            "status_messages" => [count($result) . " schedules found"],
            "draw" => $get["draw"] ?? null,
            "recordsTotal" => $countAll,
            "recordsFiltered" => $countFiltered,
            "iTotalRecords" => $countAll,
            "iTotalDisplayRecords" => $countFiltered,
            "data" => $result,
        ];

        //return $this->response->setJSON($data);
        return $this->getJsonWithCSRF($data);
    }
}
