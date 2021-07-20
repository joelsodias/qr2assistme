<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;
use DateTime;

class AdminController extends BaseAdminLteController
{


    public function viewBackendAdmin($user)
    {
        $date = new DateTime();
        
        $startDate = clone $date;
        $endDate = clone $date;

        $dayofweek = date('w', $date->getTimestamp()) + 1;
        
        $startOffset = 7 + $dayofweek - 1;
        $endOffset = 7 + 7 - $dayofweek +1;

        $startDate->modify("-$startOffset day");
        $startDateStr = $startDate->format("Y-m-d");  

        $endDate->modify("+$endOffset day");
        $endDateStr = $endDate->format("Y-m-d"); 
        $endDate->modify("-1 day");

        
        $scheduleModel = new \App\Models\ScheduleModel();

        $result = $scheduleModel->getScheduleCountFromRange($startDateStr, $endDateStr);

        $schedules = [];

        foreach ($result as $s) {
            $schedules[$s["schedule_date"]] = $s;
            $schedules[$s["schedule_date"]]["open"] = $s["schedule"] ?? 0 + $s["requested"] ?? 0;
            //$schedules[$s["schedule_date"]]["open"] = $s["schedule"] ?? 0 + $s["requested"] ?? 0;

        }

        $data = [
            "page_title" => "Painel de Controle",
            "enable_content_header" => true,
            "content_header_title" => "Home",
            //"before_sidebar" => "",
            "enable_datatables" => true,
            "startDate" => $startDate,
            "endDate" => $endDate,
            "schedules" => $schedules

        ];

        return $this->view("content/admin/admin_home_view", $data);
    }


    public function index()
    {

        $user = $_SESSION["auth"]["admin"]["local"]["user"];
        if (isset($user)) {
            if (isset($user->worker->worker_type)) {
                switch ($user->worker->worker_type) {
                    case "field":
                        return redirect()->to("/field");
                        break;
                    case "attendant":
                        return $this->viewBackendAdmin($user);
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

    public function dashboard()
    {

        $data = [
            "page_title" => "Dashboard",
            "layout" => "layouts/layout_adminlte"
        ];

        return $this->view("content/admin/admin_dashboard_view", $data);
    }

    public function notImplemented()
    {

        $data = [
            "page_title" => "Dashboard",
            "layout" => "layouts/layout_adminlte"
        ];

        return $this->view("content/admin/admin_dashboard_view", $data);
    }
}
