<?php

namespace App\Controllers;

use Ramsey\Uuid\Nonstandard\UuidV6;
use chillerlan\QRCode\QRCode;



class AdminController extends BaseAdminLteController
{

	public function viewFieldAdmin()
	{

		$data = [
			"page_title" => "Trabalho de Campo",
			"enable_content_header" => true,
			"enable_top_navbar" => false,
			"enable_sidebar" => false,
			"content_header_title" => "Menu principal",
			//"before_sidebar" => "",
			"enable_datatables" => true,
		];

		return $this->view("content/admin/field/field_admin_home_view", $data);
	}

	public function viewBackendAdmin()
	{

		$data = [
			"page_title" => "Painel de Controle",
			"enable_content_header" => true,
			"content_header_title" => "Nome da tela",
			//"before_sidebar" => "",
			"enable_datatables" => true,
		];

		return $this->view("content/admin/admin_home_view", $data);
	}


	public function index()
	{

		$user = $_SESSION["auth"]["admin"]["local"]["user"];
		if (isset($user)) {
			if (isset($user->worker->worker_type)) {

				switch ($user->worker->worker_type) {
                  
					case "field": return $this->viewFieldAdmin();
					case "attendant": return $this->viewBackendAdmin();
				}
			}
		} else {
			$_SESSION["auth"]["admin"]["connected"] = false;
			return redirect()->to("/admin/login");
		}
		
	}

	public function dashboard()
	{

		$data = [
			"layout" => "layouts/layout_adminlte"
		];

		return $this->view("content/admin/adm_dashboard_view", $data);
	}
}
