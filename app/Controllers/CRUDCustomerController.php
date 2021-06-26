<?php

namespace App\Controllers;

use App\Controllers\BaseAdminLteController;
use chillerlan\QRCode\QRCode;
use Ramsey\Uuid\Nonstandard\UuidV6;
use \App\Entities\CustomerEntity;
use \App\Views\CRUDQrObjectView;

class CRUDCustomerController extends BaseAdminLteCRUDController
{


	protected $viewClass = "App\Views\CRUDCustomerView";



	protected function getViewData()
	{

		$e = new CustomerEntity();

		return [
			"allow_insert" => true,
			"allow_update" => true,
			"allow_delete" => false,
			"insert_form_method" => "getEditForm",
			"update_form_method" => "getEditForm",
			"delete_form_method" => "getDeleteForm",

			"insert_modal_label" => "Inserir Cliente",
			"update_modal_label" => "Editar Cliente",
			"delete_modal_label" => "Remover Cliente",

			"insert_button_label" => "Inserir Novo",
			"edit_button_label" => "Editar",
			"save_button_label" => "Salvar Dados",
			"cancel_button_label" => "Cancelar",
			"close_button_label" => "Fechar",
			"reload_button_label" => "Atualizar Dados",
			"delete_confirmation_message" => "Tem certeze que deseja excluir esse cliente?",

			"dataset_key_fieldname" => "customer_uid",
			"dataset_hidden_fields" => $e->getFieldLabels(["customer_uid"]),
			"dataset_visible_fields" => $e->getFieldLabels(["customer_name", "customer_email"]),
			"page_title" => "Painel de Controle",
			"page_subtitle" => "Painel de Controle",
			"enable_content_header" => true,
			"content_header_title" => "Clientes",
			"content_header_subtitle" => "Base de Clientes",
		];
	}

	public function getList()
	{


		$get = $this->getRequest()->getGet();
		$post = $this->getRequest()->getPost();
		$body = $this->getRequest()->getBody();
		$json = $this->getRequest()->getJSON();


		$test = $this;

		$customerModel = new \App\Models\CustomerModel();
		$countAll = $customerModel->countAll();



		$builder = $customerModel->builder();

		//$builder->join("customer", "customer.customer_uid = qrobject.owner_uid", "left outer");

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

		$customers = $builder->get($get["length"] ?? null, $get["start"] ?? 0, false)->getResult();
		//$countFiltered = $builder->countAllResults(true);
		//$objs = $qrobjModel->findAll($post["length"], $post["start"]);

		// prevent internal id

		$result = [];

		foreach ($customers as $value) {
			$result[] =  [
				"customer_name" => $value->customer_name,
				"customer_uid" => $value->customer_uid,
				"customer_email" => $value->customer_email,
				"customer_admin_user_uid" => $value->customer_admin_user_uid,
				"cutomer_description" => $value->customer_description,
			];
			unset($value->id);
			unset($value->deleted_at);
		}

		$data = [
			"status_code" => 200,
			"status" => "Success",
			"status_messages" => [count($customers) . " customers found"],
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



	public function postInsert()
	{
		$data = null;
		if ($this->validateUuid($this->getRequestParam("customer_uid"), true)) {

			$record = [];
			$post = $this->getRequest()->getPost();
			$record["customer_uid"] = (string) UuidV6::uuid6();
			$record["customer_name"] = $this->getRequestParam("customer_name");
			$record["customer_email"] = $this->getRequestParam("customer_email");
			$record["customer_description"] = $this->getRequestParam("customer_description");

			$e = new \App\Entities\CustomerEntity($record);
			$model = new \App\Models\CustomerModel();

			$r = $model->insert($e, true);

			$data = [
				"status_code" => 200,
				"status" => "Success",
				"status_messages" => [" 1 customer inserted"],
			];
		} else {
			$data = [
				"status_code" => 400,
				"status" => "Bad Request",
				"status_messages" => ["Customer Id is invalid"],
			];
		}

		//return $this->response->setJSON($data);
		return $this->getJsonWithCSRF($data);
	}

	public function postUpdate()
	{
		$errors = [];
		$data = [];

		$customer_uid = $this->getRequestParam("customer_uid");

		$model = new \App\Models\CustomerModel();
		$builder = $model->builder();

		if (
			$this->validateUuid($customer_uid) 
		) {

			if (!count($errors)) {

				$builder->resetQuery();
				$builder->where("customer_uid", $customer_uid);
				$result = $builder->get(1)->getCustomResultObject("\App\Entities\CustomerEntity");

				$old = $result[0];

				$old->customer_uid = $customer_uid;
				$old->customer_name = $this->getRequestParam("customer_name") ?? $old->customer_name;
				$old->customer_email = $this->getRequestParam("customer_email") ?? $old->customer_email;
				$old->customer_description = $this->getRequestParam("customer_description") ?? $old->customer_description;

				$model->save($old);

				$data = [
					"status_code" => 200,
					"status" => "Success",
					"status_messages" => ["Record updated"],
				];

			}
		}

        

		return $this->getJsonWithCSRF($data);


	}

	public function postDelete()
	{
        $data= [];
		$id = $this->getRequestParam("customer_uid");

		$model = new \App\Models\CustomerModel();

		//$id = $model->delete($id);

		$result = $model->find($id);

		//return $this->response->setJSON($result);
		return $this->getJsonWithCSRF($data);
	}
}
