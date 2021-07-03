<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use chillerlan\QRCode\QRCode;
use Ramsey\Uuid\Nonstandard\UuidV6;

class QrCodeController extends BaseController
{
	protected $viewClass = "App\Views\BaseAdminLteView";

	
	public function index() {
	
		$data = [
			"layout" => "layouts/layout_bootstrap_clear_noresize"
		];
	
		return $this->view("content/qr_view",$data);
	}




	public function printLabels() {
		
		$qtd = 18;

		for ($i=0; $i <= $qtd-1; $i++){
		   $uuid	= $this->getNewUUidString();
		   $id[] = $uuid;  
		   $qr[] =  (new QRCode)->render(base_url('/qr/'.base64_encode('sup/obj/'. $uuid)));	
		}

		$data = [
			"qtd" => $qtd,
			"qr" => $qr,
			"id" => $id,
			"nomeEmpresa" => "AIRCON SERVICES - (XX) 99999-9999",
			"enable_content_header" => true,
			"content_header_title" => "Etiquetas QR Code",
			"content_header_subtitle" => "Impressão de Etiquetas de QR Code para afixar em Objetos",
		];

		return $this->view("content/admin/admin_qretiquetas_view",$data);

	}


	public function reader() {

		$data = [
			"layout" => "layouts/layout_bootstrap_clear"
		];
	
		return $this->view("content/qr_reader_view",$data);
	}

	public function showScan($code) {
	

		$data = [
			"layout" => "layouts/layout_bootstrap_clear_noresize",
			"code" => $code
		];
	
		return $this->view("content/qr_show_scan_view",$data);
	}


}
