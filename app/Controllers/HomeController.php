<?php

namespace App\Controllers;

use Ramsey\Uuid\Nonstandard\UuidV6;
use chillerlan\QRCode\QRCode;



class HomeController extends BaseController
{
	public function index()
	{
		return $this->view("content/home_view");
	}

	public function layouttest()
	{
		return $this->view("content/home_view");
	}





	public function landpage() {
	
		$data = [
			"layout" => "layouts/layout_bootstrap_clear_noresize"
		];
		
		return $this->view("content/landpage_view",$data);
		
		$x = new \App\Models\ChatUserModel();

		if ($x->isGoogleRegistered(1)) {
		} else {
			return redirect()->to('/login');
		}	
	}


	




	public function login() {
	
		$data = [
			"layout" => "layouts/layout_bootstrap_clear_noresize"
		];
	
		return $this->view("content/login_view",$data);
	}




}
