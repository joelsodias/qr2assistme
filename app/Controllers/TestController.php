<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Ramsey\Uuid\Uuid;
use chillerlan\QRCode\QRCode;
use App\Helpers\CustomHelper;

class TestController extends BaseController
{
	public function index()
	{
		//
	}

	public function parseviewtest()
	{
		$data = [
			'result' => '200',
			'list' => CustomHelper::array2records(array('opt1', 'opt2', 'opt3', 'opt4'), "item"),
			'y' => array('a', 'b'),
			'x' => array_combine(array_pad(array(), 4, 'value'), array('opt1', 'opt2', 'opt3', 'opt4')),
			'list2' => array_combine(array_pad(array(), 2, 'value'), array('x', 'y')),

		];
		return $this->parseView('content/home_parse', $data);
	}

	public function status404test()
	{
		return $this->response->setStatusCode(404, 'Nope. Not here.');
	}

	public function status501test()
	{
		$this->response->setStatusCode(Response::HTTP_NOT_IMPLEMENTED);
	    if (method_exists($this->response,'setHeader')) {
			 $this->response->setHeader('Content-type', 'text/html');
		}
			 $this->response->noCache();
	}

	public function simulateStatus($status)
	{
		$validStatuses = array(100, 101, 102, 103, 200, 201, 202, 203, 204, 205, 206, 207, 208, 226, 300, 301, 302, 303, 304, 305, 306, 307, 308, 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 421, 422, 423, 424, 425, 426, 428, 429, 431, 451, 499, 500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511, 599);
		if (!in_array($status, $validStatuses)) {
			$this->response->setStatusCode(Response::HTTP_BAD_REQUEST,'Bad Request. The status code used is not valid for this service! Try any other!');
		} else {
			 
			$this->response->setStatusCode($status,($status == 400 ) ? "Bad Request (Expected response!)" : '');

			$this->response->setHeader('Content-type', 'text/html');
			$this->response->noCache();
		}
	}

	public function headerstest()
	{
		$response =
			$this->response->setHeader('Location', 'http://example.com')
			->setHeader('WWW-Authenticate', 'Negotiate');
	}



	public function jsontest()
	{
		$data = [
			'test_param' => 'meu titulo',
			'list' => \CustomHelper::array2records(array('opt1', 'opt2', 'opt3', 'opt4'), "item"),
			'y' => array('a', 'b'),
			'x' => array_combine(array_pad(array(), 4, 'value'), array('opt1', 'opt2', 'opt3', 'opt4')),
			'list2' => array_combine(array_pad(array(), 2, 'value'), array('x', 'y')),

		];
		return $this->response->setJSON($data);
	}
	
	public function uuidtest(){
		
		$uuid = $this->getNewUUidString();
		
		$data= ["uuid" => $uuid];
		//return $this->response->setJSON($data);
		return $this->getJsonWithCSRF($data);
	}

	public function shortidtest(){
		
		$shortid = CustomHelper::shortUid(10);
		$shortid = str_split($shortid,5);
		$shortid = join("-",$shortid); 

		$data= ["shortid" => $shortid];
		//return $this->response->setJSON($data);
		return $this->getJsonWithCSRF($data);
	}

    public function qrcodetest(){

		$uuid = $this->getNewUUidString();

		$data = 'http://localhost/qr/'.$uuid; //inserindo a URL do iMasters
		return '<img src="' . (new QRCode)->render($data) . '" />'; //gerando o QRCode em uma tag img

	}


}
