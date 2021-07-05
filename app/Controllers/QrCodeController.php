<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use chillerlan\QRCode\QRCode;
use Ramsey\Uuid\Nonstandard\UuidV6;
use CodeIgniter\Exceptions;
use CodeIgniter\Exceptions\PageNotFoundException;

class QrCodeController extends BaseController
{
	protected $viewClass = "App\Views\BaseAdminLteView";


	protected function getEncodedLabelQrCode(string $uuid = null, string $prepend = "")
	{
		$private = getenv("app.qrcode.label.url.secret");
		$path = getenv("app.qrcode.label.url.path");
		$uuid = $uuid ?? $this->getNewUUidString();
		$part = base64_encode($prepend . '|obj|' . $uuid);
		$sha1 = sha1($part . '|' . $private);
		$finalurl = base_url($path . '/' . base64_encode($part . '|' . $sha1));
		return (new QRCode)->render($finalurl);
	}

	protected function getDecodedLabelQrCode(string $code)
	{
		$private = getenv("app.qrcode.label.url.secret");
		$path = getenv("app.qrcode.label.url.path");
		try {
			$step1 = base64_decode($code);
			$step2 = explode("|", $step1);
			if (count($step2) == 2) {
				$shaReceived = $step2[1];
				$shaProduced = sha1($step2[0] . '|' . $private);
				if ($shaReceived == $shaProduced) {
					$step3 = base64_decode($step2[0]);
					$step4 = explode('|obj|', $step3);
					return [
						"prepend" => $step4[0],
						"object_uid" => $step4[1],
					];
				} else return null;
			} else return null;
		} catch (\Exception $error) {
			return null;
		}
	}




	public function index()
	{

		$data = [
			"pageTitle" => "Qr View",
			"layout" => "layouts/layout_bootstrap_clear_noresize"
		];

		return $this->view("content/qr_view", $data);
	}




	public function printLabels()
	{

		$qtd = 18;

		for ($i = 0; $i <= $qtd - 1; $i++) {
			$uuid	= $this->getNewUUidString();
			$id[] = $uuid;
			//$qr[] =  (new QRCode)->render(base_url('/code/' . base64_encode('sup/obj/' . $uuid)));
			$qr[] = $this->getEncodedLabelQrCode($uuid, 'aircon');
		}

		
		$label_header = getenv("app.qrcode.label.header");
		$data = [
			"qtd" => $qtd,
			"qr" => $qr,
			"id" => $id,
			"label_header" => $label_header,
			"enable_content_header" => true,
			"content_header_title" => "Etiquetas QR Code",
			"content_header_subtitle" => "Impressão de Etiquetas de QR Code para afixar em Objetos",
		];

		return $this->view("content/admin/admin_qretiquetas_view", $data);
	}

	public function reprintLabels()
	{

		$qtd = 18;

		$objectModel = new \App\Models\QrObjectModel();
		$objects = $objectModel->findAll(20);

		if (isset($objects) && is_array($objects) && count($objects) > 0) {


			foreach ($objects as $o) {
				$uuid	= $o->object_uid;
				$id[] = $uuid;
				$qr[] = $this->getEncodedLabelQrCode($uuid, 'aircon');
			}

			$label_header = getenv("app.qrcode.label.header");
			$data = [
				"qtd" => $qtd,
				"qr" => $qr,
				"id" => $id,
				"label_header" => $label_header,
				"enable_content_header" => true,
				"content_header_title" => "Etiquetas QR Code",
				"content_header_subtitle" => "Impressão de Etiquetas de QR Code para afixar em Objetos",
			];
		}

		return $this->view("content/admin/admin_qretiquetas_view", $data);
	}


	public function reader()
	{

		$data = [
			"layout" => "layouts/layout_bootstrap_clear"
		];

		return $this->view("content/qr_reader_view", $data);
	}


	public function showScan($code)
	{

		$decoded = null;
		$object = null;

		unset($_SESSION["QR"]["object"]);

		$decoded = $this->getDecodedLabelQrCode($code);

		if ($decoded && isset($decoded["object_uid"]) && $this->validateUuid($decoded["object_uid"])) {

			$objectModel = new \App\Models\QrObjectModel();
			$object = $objectModel->getObject($decoded["object_uid"]);

			if ($object) {

                $_SESSION["QR"]["object"] = $object;

				$data = [
					"layout" => "layouts/layout_bootstrap_clear_noresize",
					"object" => $object,
				];
		
				return $this->view("content/qr_show_scan_view", $data);



			} else return redirect()->to("/land");
		} else return redirect()->to("/land");
	}
}
