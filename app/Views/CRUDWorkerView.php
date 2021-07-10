<?php

namespace App\Views;

use App\Views\BaseAdminLteCRUDView;
use App\Helpers\CustomHelper;

class CRUDWorkerView extends BaseAdminLteCRUDView
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
			<label class="col-md-2 col-form-label">Nome</label>
			<div class="col-md-10">
				<input required type="text" name="worker_name" id="<?= $form_method ?>-field-worker_name" class="form-control" placeholder="Digite o nome">
				<input name="old-worker_name" id="<?= $form_method ?>-field-old-worker_name" type="hidden">
				<input name="worker_uid" id="<?= $form_method ?>-field-worker_uid" type="hidden">
			</div>
		</div>

		<div class="form-group row">
			<label class="col-md-2 col-form-label">E-mail</label>
			<div class="input-group col-md-10">
				<div class="input-group-prepend">
					<div class="input-group-text">@</div>
				</div>
				<input required name="worker_email" id="<?= $form_method ?>-field-worker_email" type="text" class="form-control" placeholder="Digite o e-mail" aria-label="" aria-describedby="">
				<input name="old-worker_email" id="<?= $form_method ?>-field-old-worker_email" type="hidden">

			</div>
		</div>


		<div class="form-group row">
		<label class="col-md-2 col-form-label">Tipo</label>
			<div class="input-group col-md-10">
				<select required name="worker_type" id="<?= $form_method ?>-field-worker_type" class="form-control">
					<option value="">Escolha o tipo...</option>
					<option value="field">Técnico de Campo</option>
					<option value="attendant">Atendente</option>
				</select>
				<input name="old-worker_type" id="<?= $form_method ?>-field-old-worker_type" type="hidden">
			</div>
		</div>


		<div class="form-group row">
			<label class="col-md-2 col-form-label">Descrição</label>
			<div class="col-md-10">
				<textarea name="worker_description" id="<?= $form_method ?>-field-worker_description" rows="5" cols="50" class="form-control" placeholder="Campo disponível para digitação de complemento de informações sobre o objeto que podem sempre ser apresentadas quando o objeto for consultado pelo cliente"></textarea>
				<input name="old-worker_description" id="<?= $form_method ?>-field-old-worker_description" type="hidden">
			</div>
		</div>
<?php


	}
}
