<?php

namespace App\Views;

use App\Helpers\CustomHelper;

class BaseAdminLteCRUDView extends BaseAdminLteView {


	public function getEditForm($form_method)
	{
		return "";
	}

	public function view(string $view = null, $data = null) : string
	{
		$defaultData = [

		];

		$mergedData = CustomHelper::array_merge_recursive_distinct($defaultData, $data);
		
		$view = $view ?? "content/admin/crud/admin_base_crud_view";

		return parent::view($view, $mergedData);
	}



}