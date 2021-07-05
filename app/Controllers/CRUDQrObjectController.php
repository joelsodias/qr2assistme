<?php

namespace App\Controllers;

use App\Controllers\BaseAdminLteController;
use chillerlan\QRCode\QRCode;
use Ramsey\Uuid\Nonstandard\UuidV6;
use \App\Entities\QrObjectEntity;
use \App\Views\CRUDQrObjectView;


class CRUDQrObjectController extends BaseAdminLteCRUDController
{

	protected $viewClass = "App\Views\CRUDQrObjectView";

	protected function getViewData()
	{

		$e = new QrObjectEntity();

		return [
			"allow_insert" => true,
			"allow_update" => true,
			"allow_delete" => false,
			"insert_form_method" => "getEditForm",
			"update_form_method" => "getEditForm",
			"delete_form_method" => "getDeleteForm",
			"custom_html_method" => "getCustomHTML",

			"insert_modal_label" => "Inserir Objeto",
			"update_modal_label" => "Editar Objeto",
			"delete_modal_label" => "Remover Objeto",

			"insert_button_label" => "Inserir Novo",
			"edit_button_label" => "Editar",
			"save_button_label" => "Salvar Dados",
			"cancel_button_label" => "Cancelar",
			"close_button_label" => "Fechar",
			"reload_button_label" => "Atualizar Dados",
			"delete_confirmation_message" => "Are you sure to delete this record?",

			"dataset_key_fieldname" => "object_uid",
			"dataset_hidden_fields" => $e->getFieldLabels(["object_owner_uid"]),
			"dataset_visible_fields" => $e->getFieldLabels(["customer_name", "object_name", "object_serial", "object_model"]),
			"page_title" => "Painel de Controle",
			"page_subtitle" => "Painel de Controle",
			"enable_content_header" => true,
			"content_header_title" => "QR Objects",
			"content_header_subtitle" => "Base de objetos",
		];
	}

	public function getList()
	{


		$get = $this->getRequest()->getGet();
		$post = $this->getRequest()->getPost();
		$body = $this->getRequest()->getBody();
		$json = $this->getRequest()->getJSON();


		$test = $this;

		$qrobjModel = new \App\Models\QrObjectModel();
		$countAll = $qrobjModel->countAll();


		$builder = $qrobjModel->builder();

		$builder->join("customer", "customer.customer_uid = qrobject.object_owner_uid", "left outer");

		if (isset($get["columns"]) && count($get["columns"])) {
			foreach ($get["columns"] as $key => $value) {
				if (isset($value["search"]) && isset($value["search"]["value"]) && ($value["search"]["value"] != "")) {
					$builder->like($value["data"], $value["search"]["value"], "both");
				}
			}

			if (isset($get["order"]) && count($get["order"])) {
				foreach ($get["order"] as $key => $value) {

					$colnum =  $value["column"];
					$colnum =  $get["columns"][$colnum]["data"];
					$dir = $value["dir"];

					$builder->orderBy($colnum, $dir);
				}
			}
		}



		$countFiltered = $builder->countAllResults(false);

		$objs = $builder->get($get["length"] ?? null, $get["start"] ?? 0, false)->getResult();
		//$countFiltered = $builder->countAllResults(true);
		//$objs = $qrobjModel->findAll($post["length"], $post["start"]);

		// prevent internal id

		$result = [];

		foreach ($objs as $value) {
			$result[] =  [
				"customer_name" => $value->customer_name,
				"object_uid" => $value->object_uid,
				"object_owner_uid" => $value->object_owner_uid,
				"object_name" => $value->object_name,
				"object_serial" => $value->object_serial,
				"object_model" => $value->object_model,
				"object_description" => $value->object_description,
			];
			unset($value->id);
			unset($value->deleted_at);
		}

		$data = [
			"status_code" => 200,
			"status" => "Success",
			"status_messages" => [count($objs) . " objects found"],
			"draw" => $get["draw"] ?? null,
			"recordsTotal" => $countAll,
			"recordsFiltered" => $countFiltered,
			"iTotalRecords" => $countAll,
			"iTotalDisplayRecords" => $countFiltered,
			"data" => $result,
		];


		return $this->getJsonWithCSRF($data);
	}

	public function insert($str)
	{

		$qrobjModel = new \App\Models\QrObjectModel();

		$code = base64_decode($str);
		$url = base_url('qr') . "/";
		$code = str_replace($url, '', $code);
		$decoded = base64_decode($code);
		$decoded = str_replace('sup/', '', $decoded);

		$data = [
			'object_uid' => $decoded,
			'description'    => 'Qualquer descrição',
			'owner_id' => 1,

		];

		$id = $qrobjModel->insert($data);

		$data = $qrobjModel->find($id);

		//return $this->response->setJSON($result);
		return $this->getJsonWithCSRF($data);
	}

	public function postInsert()
	{
		$data = null;
		if ($this->validateUuid($this->getRequestParam("object_uid"))) {

			$record = [];

			$record["object_uid"] =  $this->getNewUUidString();
			$record["object_owner_uid"] = $this->getRequestParam("object_owner_uid");
			$record["object_name"] = $this->getRequestParam("object_name");
			$record["object_model"] = $this->getRequestParam("object_model");
			$record["object_serial"] = $this->getRequestParam("object_serial");
			$record["object_description"] = $this->getRequestParam("object_description");

			$e = new \App\Entities\QrObjectEntity($record);
			$qrObjModel = new \App\Models\QrObjectModel();

			$r = $qrObjModel->insert($e, true);

			$data = [
				"status_code" => 200,
				"status" => "Success",
				"status_messages" => [" 1 object inserted"],
			];
		} else {
			$data = [
				"status_code" => 400,
				"status" => "Bad Request",
				"status_messages" => ["Object Id is invalid"],
			];
		}

		//return $this->response->setJSON($data);
		return $this->getJsonWithCSRF($data);
	}

	public function postUpdate()
	{
		$data = [];
		$record = [];
		$errors = [];

		$old_object_uid = $this->getRequestParam("old-object_uid");
		$new_object_uid = $this->getRequestParam("object_uid");

		$qrObjModel = new \App\Models\QrObjectModel();
		$builder = $qrObjModel->builder();

		if (
			$this->validateUuid($old_object_uid) &&
			$this->validateUuid($new_object_uid)
		) {

			$new = null;

			if ($old_object_uid != $new_object_uid) {

				$builder->where("object_uid", $new_object_uid);
				$new = $builder->get(1)->getCustomResultObject("\App\Entities\QrObjectEntity");

				if ($new) {
					$errors[] = "Id do objeto (object_uid) selecionado já está associado a outro objeto cadastrado";
				}
			}

			if (!count($errors)) {

				$builder->resetQuery();
				$builder->where("object_uid", $old_object_uid);
				$result = $builder->get(1)->getCustomResultObject("\App\Entities\QrObjectEntity");

				$old = $result[0];

				$old->object_uid = $new_object_uid ?? $old->object_uid;
				$old->object_owner_uid = $this->getRequestParam("object_owner_uid") ?? $old->object_owner_uid;
				$old->object_name = $this->getRequestParam("object_name") ?? $old->object_name;
				$old->object_serial = $this->getRequestParam("object_serial") ?? $old->object_serial;
				$old->object_model = $this->getRequestParam("object_model") ?? $old->object_model;
				$old->object_description = $this->getRequestParam("object_description") ?? $old->object_description;

				$qrObjModel->save($old);

			}
		}

		return $this->getJsonWithCSRF($data);
	
	}

	public function postDelete()
	{
		$data = [];
		$id = $this->getRequestParam("object_uid");

		$qrobjModel = new \App\Models\QrObjectModel();

		$id = $qrobjModel->delete($id);

		$result = $qrobjModel->find($id);

		//return $this->response->setJSON($result);
		return $this->getJsonWithCSRF($data);
	}
}
