<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services as AppServices;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Nonstandard\UuidV6;
use chillerlan\QRCode\QRCode;
use App\Helpers\CustomHelper;
use App\Views\BaseAdminLteCRUDView;



class BaseAdminLteCRUDController extends BaseAdminLteController
{

	protected $viewClass = "App\Views\BaseAdminLteCRUDView";



	protected function getViewData()
	{
		return [];
	}

	public function index()
	{
		$class = get_class($this);

	
		$defaultData = [
			"dataset_array" => null,
			"allow_insert" => false,
			"allow_update" => false,
			"allow_delete" => false,
			"insert_button_label" => "Add New",
			"insert_modal_label" => "Add New Item",
			"insert_form_method" => false,
			"update_form_method" => false,
			"delete_form_method" => false,
			"custom_html_method" => false,
			"reload_button_label" => "Reload Data",
			"save_button_label" => "Save",
			"cancel_button_label" => "Cancel",
			"close_button_label" => "Close",
			"row_label_buttons" => false,
			"edit_button_label" => "Edit",
			"delete_confirmation_message" => "Tem certteza que deseja excluir esse registro?",
			"page_title" => "Painel de Controle",
			"list_url" =>  route_to($class . '::getList'),
			"insert_url" =>  route_to($class . '::postInsert'),
			"update_url" =>  route_to($class . '::postUpdate'),
			"delete_url" =>  route_to($class . '::postDelete'),
			"enable_content_header" => false,
			"content_header_title" => "{\$content_header_title}",
			"content_header_subtitle" => "{\$content_header_subtitle}",
			//"before_sidebar" => "",
			"enable_datatables" => true,
			"controller" => $this,
		];



		$data = $this->getViewData();

		$data = CustomHelper::array_merge_recursive_distinct($defaultData, $data);

		return $this->view("content/admin/crud/admin_base_crud_view", $data);
	}

    protected function defaultNotImplementedResult () {
		$data = [
			"status_code" => 501,
			"status" => "Not Implemented",
			"status_messages" => ["Method not implemented"],
			"result_data" =>[],
		];
		return $this->response->setJSON($data);		
	}

	public function getList()
	{
		return $this->defaultNotImplementedResult();
	}
	
	public function postSave()
	{
		return $this->defaultNotImplementedResult();
	}
	
	public function postDelete()
	{
		return $this->defaultNotImplementedResult();
	}



}
