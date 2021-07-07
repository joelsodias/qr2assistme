<?php

namespace App\Controllers;

use CodeIgniter\Exceptions\PageNotFoundException;



class AdminController extends BaseAdminLteController
{


	public function viewBackendAdmin($user)
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
			} else throw PageNotFoundException::forPageNotFound();
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
