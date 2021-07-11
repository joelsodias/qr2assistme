<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;

class AdminFieldController extends BaseAdminLteController
{
    public function logout()
    {
        // fake method to enable logout (filter auth and route need that)
        return "";
    }

    public function viewFieldAdmin($user)
    {

        $scheduleModel = new \App\Models\ScheduleModel();
        $schedules = $scheduleModel->getSchedules($user->worker->worker_uid, array('scheduled', 'rescheduled'), date('Y-m-d'));

        $data = [
            "page_title" => "Trabalho de Campo",
            "enable_content_header" => true,
            "enable_top_navbar" => false,
            "enable_sidebar" => false,
            "content_header_title" => "Agendamentos",
            //"before_sidebar" => "",
            "enable_datatables" => true,
            "schedules" => $schedules,
        ];

        return $this->view("content/admin/field/field_admin_home_view", $data);
    }

    public function index()
    {

        $user = $_SESSION["auth"]["admin"]["local"]["user"];
        if (isset($user)) {
            if (isset($user->worker->worker_type)) {
                switch ($user->worker->worker_type) {
                    case "field":
                        return $this->viewFieldAdmin($user);
                        break;
                    case "attendant":
                        return redirect()->to("/admin");
                        break;
                    default:
                        throw PageNotFoundException::forPageNotFound();
                        break;
                }
            } else {
                throw PageNotFoundException::forPageNotFound();
            }
        } else {
            $_SESSION["auth"]["admin"]["connected"] = false;
            return redirect()->to("/admin/login");
        }
    }
}
