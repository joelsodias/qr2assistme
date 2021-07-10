<?php

namespace App\Views;

use App\Views\BaseAdminLteCRUDView;
use App\Helpers\CustomHelper;

class CRUDQrObjectView extends BaseAdminLteCRUDView {

	public function getCustomHTML($data = null)
	{

?>

		<div class="modal fade" id="myModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Modal 1</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

					</div>
					<div class="container"></div>
					<div class="modal-body">Content for the dialog / modal goes here.
						<br>
						<br>
						<br>
						<p>more content</p>
						<br>
						<br>
						<br> <a data-toggle="modal" data-backdrop="static" href="#myModal2" class="btn btn-primary">Launch modal</a>

					</div>
					<div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
						<a href="#" class="btn btn-primary">Save changes</a>

					</div>
				</div>
			</div>
		</div>
		<div class="modal fade r-otate" id="myModal2">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Modal 2</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

					</div>
					<div class="container"></div>
					<div class="modal-body">Content for the dialog / modal goes here.
						<br>
						<br>
						<p>come content</p>
						<br>
						<br>
						<br> <a data-toggle="modal" data-backdrop="static" href="#myModal3" class="btn btn-primary">Launch modal</a>

					</div>
					<div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
						<a href="#" class="btn btn-primary">Save changes</a>

					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="myModal3">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Modal 3</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

					</div>
					<div class="container"></div>
					<div class="modal-body">Content for the dialog / modal goes here.
						<br>
						<br>
						<br>
						<br>
						<br> <a data-toggle="modal" data-backdrop="static" href="#myModal4" class="btn btn-primary">Launch modal</a>

					</div>
					<div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
						<a href="#" class="btn btn-primary">Save changes</a>

					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="myModal4">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Modal 4</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

					</div>
					<div class="container"></div>
					<div class="modal-body">Content for the dialog / modal goes here.
						<br>
						<br>
						<br>
						<br>
						<br> <a data-toggle="modal" data-backdrop="static" href="#myModal5" class="btn btn-primary">Launch modal</a>

					</div>
					<div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
						<a href="#" class="btn btn-primary">Save changes</a>

					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="myModal5">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Modal 5</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

					</div>
					<div class="container"></div>
					<div class="modal-body">Content for the dialog / modal goes here.</div>
					<div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn">Close</a>
						<a href="#" class="btn btn-primary">Save changes</a>

					</div>
				</div>
			</div>
		</div>
		<a data-toggle="modal" data-backdrop="static" href="#myModal" class="btn btn-primary">Launch modal</a>
	<?php
	}

	public function getEditForm($form_method)
	{
	?>
		

		<div class="form-group row">
			<label class="col-md-2 col-form-label">Nome/Descrição Resumida</label>
			<div class="col-md-10">
				<input required type="text" name="object_name" id="<?= $form_method ?>-field-object_name" class="form-control" placeholder="Digite resumidamente o nome do objeto">
				<input name="old-object_name" id="<?= $form_method ?>-field-old-object_name" type="hidden">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Propriétário</label>
			<div class="input-group col-md-10">
				<div class="input-group-prepend">
					<button id="<?= $form_method ?>-search-customer" class="btn btn-outline-secondary" type="button">
						<span>Busca</span>
						<i class="fas fa-search"></i>
					</button>
				</div>
				<input required disabled name="customer_name" id="<?= $form_method ?>-field-customer_name" type="text" class="form-control" placeholder="Utilize a busta para selecionar o proprietário" aria-label="" aria-describedby="">
				<input name="object_owner_uid" id="<?= $form_method ?>-field-object_owner_uid" type="hidden">
				<input name="old-object_owner_uid" id="<?= $form_method ?>-field-old-object_owner_uid" type="hidden">

			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Id do Objeto</label>
			<div class="input-group col-md-10">
				<div class="input-group-prepend">
					<button id="<?= $form_method ?>-scan-code" class="btn btn-outline-secondary" type="button">
						<span>Scan QR Code</span>
						<i class="fas fa-qrcode"></i>
					</button>
				</div>
				<input required name="object_uid" id="<?= $form_method ?>-field-object_uid" type="text" class="form-control" placeholder="Utilize o leitor ou digite o id do objeto disponível na etiqueta" aria-label="" aria-describedby="">
				<input name="old-object_uid" id="<?= $form_method ?>-field-old-object_uid" type="hidden">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Serial Number</label>
			<div class="col-md-10">
				<input required type="text" name="object_serial" id="<?= $form_method ?>-field-object_serial" class="form-control" placeholder="Digite o número serial do equipamento">
				<input name="old-object_serial" id="<?= $form_method ?>-field-old-object_serial" type="hidden">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Modelo</label>
			<div class="col-md-10">
				<input required type="text" name="object_model" id="<?= $form_method ?>-field-object_model" class="form-control" placeholder="Digite o identificador de modelo do equipamento">
				<input name="old-object_model" id="<?= $form_method ?>-field-old-object_model" type="hidden">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-md-2 col-form-label">Descrição</label>
			<div class="col-md-10">
				<textarea name="description" id="<?= $form_method ?>-field-description" rows="5" cols="50" class="form-control" placeholder="Campo disponível para digitação de complemento de informações sobre o objeto que podem sempre ser apresentadas quando o objeto for consultado pelo cliente"></textarea>
				<input name="old-description" id="<?= $form_method ?>-field-old-description" type="hidden">
			</div>
		</div>
<?php


	}





}