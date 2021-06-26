<?php

namespace App\Views;

use App\Views\BaseAdminLteCRUDView;
use App\Helpers\CustomHelper;

class CRUDCustomerView extends BaseAdminLteCRUDView
{

	public function getCustomHTML($data = null)
	{

    ?>
<!-- no custom HTML -->
	<?php
	}

	public function getEditForm($form_method)
	{
	?>

		<div class="form-group row">
			<label class="col-md-2 col-form-label">Nome do cliente</label>
			<div class="col-md-10">
				<input required type="text" name="customer_name" id="<?= $form_method ?>-field-customer_name" class="form-control" placeholder="Digite o nome do cliente">
				<input name="old-customer_name" id="<?= $form_method ?>-field-old-customer_name" type="hidden">
				<input name="customer_uid" id="<?= $form_method ?>-field-customer_uid" type="hidden">
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-2 col-form-label">E-mail do cliente</label>
			<div class="input-group col-md-10">
				<div class="input-group-prepend">
				<div class="input-group-text">@</div>
				</div>
				<input required name="customer_email" id="<?= $form_method ?>-field-customer_email" type="text" class="form-control" placeholder="Digite o e-mail do cliente" aria-label="" aria-describedby="">
				<input name="old-customer_email" id="<?= $form_method ?>-field-old-customer_email" type="hidden">

			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Descrição</label>
			<div class="col-md-10">
				<textarea name="customer_description" id="<?= $form_method ?>-field-customer_description" rows="5" cols="50" class="form-control" placeholder="Campo disponível para digitação de complemento de informações sobre o objeto que podem sempre ser apresentadas quando o objeto for consultado pelo cliente"></textarea>
				<input name="old-customer_description" id="<?= $form_method ?>-field-old-customer_description" type="hidden">
			</div>
		</div>
<?php


	}
}
