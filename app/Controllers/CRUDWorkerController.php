<?php

namespace App\Controllers;

use App\Controllers\BaseAdminLteController;
use chillerlan\QRCode\QRCode;
use Ramsey\Uuid\Nonstandard\UuidV6;
use \App\Entities\WorkerEntity;
use \App\Views\CRUDWorkertView;

class CRUDWorkerController extends BaseAdminLteCRUDController
{


	protected $viewClass = "App\Views\CRUDWorkerView";



	protected function getViewData()
	{

		$e = new WorkerEntity();

		return [
			"allow_insert" => true,
			"allow_update" => true,
			"allow_delete" => false,
			"insert_form_method" => "getEditForm",
			"update_form_method" => "getEditForm",
			"delete_form_method" => "getDeleteForm",

			"insert_modal_label" => "Inserir",
			"update_modal_label" => "Editar",
			"delete_modal_label" => "Remover",

			"insert_button_label" => "Inserir Novo",
			"edit_button_label" => "Editar",
			"save_button_label" => "Salvar Dados",
			"cancel_button_label" => "Cancelar",
			"close_button_label" => "Fechar",
			"reload_button_label" => "Atualizar Dados",
			"delete_confirmation_message" => "Tem certeze que deseja excluir esse registro?",

			"dataset_key_fieldname" => "worker_uid",
			"dataset_hidden_fields" => $e->getFieldLabels(["worker_uid", "worker_type"]),
			"dataset_visible_fields" => $e->getFieldLabels(["worker_name", "worker_email","worker_type_translated"]),
			"page_title" => "Painel de Controle",
			"page_subtitle" => "Painel de Controle",
			"enable_content_header" => true,
			"content_header_title" => "Colaboradores",
			"content_header_subtitle" => "Base de Colaboradores",
		];
	}

	public function getList()
	{


		$get = $this->getRequest()->getGet();
		$post = $this->getRequest()->getPost();
		$body = $this->getRequest()->getBody();
		$json = $this->getRequest()->getJSON();


		$test = $this;

		$model = new \App\Models\WorkerModel();
		$countAll = $model->countAll();



		$builder = $model->builder();

		$builder = $this->defaultDatatablesSearchAction($builder, $get);

		$countFiltered = $builder->countAllResults(false);

		$result = $builder->get($get["length"] ?? null, $get["start"] ?? 0, false);
		$records = $result->getResult("\App\Entities\WorkerEntity");
		//$countFiltered = $builder->countAllResults(true);
		//$objs = $qrobjModel->findAll($post["length"], $post["start"]);

		// prevent internal id

		$result = [];

		foreach ($records as $value) {
			$result[] =  [
				"worker_name" => $value->worker_name,
				"worker_uid" => $value->worker_uid,
				"worker_type" => $value->worker_type,
				"worker_type_translated" => $value->worker_type_translated,
				"worker_email" => $value->worker_email,
				"worker_avatar" => $value->worker_avatar,
				"worker_description" => $value->worker_description,
			];
			unset($value->id);
			unset($value->deleted_at);
		}

		$data = [
			"status_code" => 200,
			"status" => "Success",
			"status_messages" => [count($records) . " customers found"],
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
		if ($this->validateUuid($this->getRequestParam("worker_uid"), true)) {

			$record = [];
			$post = $this->getRequest()->getPost();
			$record["worker_uid"] = $this->getNewUUidString();
			$record["worker_name"] = $this->getRequestParam("worker_name");
			$record["worker_email"] = $this->getRequestParam("worker_email");
			$record["worker_description"] = $this->getRequestParam("worker_description");

			$e = new \App\Entities\CustomerEntity($record);
			$model = new \App\Models\CustomerModel();

			$r = $model->insert($e, true);

			$data = [
				"status_code" => 200,
				"status" => "Success",
				"status_messages" => [" 1 worker inserted"],
			];
		} else {
			$data = [
				"status_code" => 400,
				"status" => "Bad Request",
				"status_messages" => ["Worker Id is invalid"],
			];
		}

		//return $this->response->setJSON($data);
		return $this->getJsonWithCSRF($data);
	}

	public function postUpdate()
	{
		$errors = [];
		$data = [];

		$uid = $this->getRequestParam("worker_uid");

		$model = new \App\Models\WorkerModel();
		$builder = $model->builder();

		if (
			$this->validateUuid($uid) 
		) {

			if (!count($errors)) {

				$builder->resetQuery();
				$builder->where("worker_uid", $uid);
				$result = $builder->get(1)->getCustomResultObject("\App\Entities\WorkerEntity");

				$old = $result[0];

				$old->worker_uid = $uid;
				$old->worker_name = $this->getRequestParam("worker_name") ?? $old->worker_name;
				$old->worker_email = $this->getRequestParam("worker_email") ?? $old->worker_email;
				$old->worker_type = $this->getRequestParam("worker_type") ?? $old->worker_type;
				$old->worker_description = $this->getRequestParam("worker_description") ?? $old->worker_description;

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
		$id = $this->getRequestParam("worker_uid");

		$model = new \App\Models\CustomerModel();

		//$id = $model->delete($id);

		$result = $model->find($id);

		//return $this->response->setJSON($result);
		return $this->getJsonWithCSRF($data);
	}
}
