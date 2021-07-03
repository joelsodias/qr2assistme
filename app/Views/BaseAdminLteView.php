<?php

namespace App\Views;

use App\Helpers\CustomHelper;
use stdClass;

class BaseAdminLteView extends BaseView {

	public function addSidebarCircleImageLink(string $text = "", string $image = "", string $uri = "#")
	{
		return
			"<!-- Circle Image Link --> "
			. "<a href=\"$uri\" class=\"brand-link\"> "
			. "<img src=\"$image\" class=\"brand-image img-circle elevation-3\" style=\"opacity: .8\">"
			. "<span class=\"brand-text font-weight-light\">$text</span>"
			. "</a>";
	}

	public function addSidebarUserPanel(string $name = "", string $image = "",  string $uri = "#")
	{
		return
			"<!-- Sidebar user panel -->"
			. "	<div class=\"user-panel mt-3 pb-3 mb-3 d-flex\">"
			. "	<div class=\"image\">"
			. "	<img src=\"$image\" class=\"img-circle elevation-2\" alt=\"User Image\">"
			. "	</div>"
			. "	<div class=\"info\">"
			. "	<a href=\"$uri\" class=\"d-block\">$name</a>"
			. "	</div>"
			. "</div>";
	}

	public function addSidebarSearchForm(string $baseid = "sidebar", $placeholder = "Search")
	{
		return
			" <!-- SidebarSearch Form --> "
			. " <div id=\"$baseid-form\" class=\"form-inline\">"
			. "   <div class=\"input-group\" data-widget=\"$baseid-sidebar-search\">"
			. " 	<input id=\"$baseid-form-input\" class=\"form-control form-control-sidebar\" type=\"search\" placeholder=\"$placeholder\" aria-label=\"Search\">"
			. " 	<div class=\"input-group-append\">"
			. " 	  <button id=\"$baseid-form-button\" class=\"btn btn-sidebar\">"
			. " 		<i class=\"fas fa-search fa-fw\"></i>"
			. " 	  </button>"
			. " 	</div>"
			. "   </div>"
			. " </div>";
	}

	public function addSidebarMenu(string $baseid = "sidebar", array $menu_items = [])
	{
		$menu =
			"<!-- Sidebar Menu -->"
			. "<nav class=\"mt-2\">"
			. "  <ul id=\"$baseid-sidebar-menu\" class=\"nav nav-pills nav-sidebar flex-column\" data-widget=\"treeview\" role=\"menu\" data-accordion=\"false\">";
		foreach ($menu_items as $item) {
			$menu .=   $item;
		}
		$menu .= "</ul></nav>";
		return $menu;
	}


	public function addTreeViewMenuItem(string $baseid = "sidebar", string $menu_title = "", string $icon = "far fa-circle", bool $start_opened = false, array $menu_items = [])
	{
		$menu =
			"<!-- Sidebar TreeViewMenuItem -->"
			. "<li id=\"$baseid-TreeViewMenu\" class=\"nav-item " . (($start_opened) ? " menu-open " : "") . "\">"
			. "<a href=\"#\" class=\"nav-link active\">"
			. "  <i class=\"nav-icon $icon\"></i>"
			. "  <p> "
			. "	$menu_title "
			. "	<i class=\"right fas fa-angle-left\"></i>"
			. "  </p> "
			. "</a> "
			. "<ul class=\"nav nav-treeview\">";


		foreach ($menu_items as $item) {
			$menu .=   $item;
		}


		$menu .= "</ul></li>";

		return $menu;
	}

	public function addLinkMenuItem(string $baseid = "sidebar", string $title = "Option", string $uri = "#", string $icon = "far fa-circle")
	{
		return
			"<!-- LinkMenuItem -->"
			. "<li class=\"nav-item\">"
			. "	<a id=\"$baseid-item-link\" href=\"$uri\" class=\"nav-link\">"
			. "	  <i class=\"nav-icon $icon\"></i>"
			. "	  <p>$title</p>"
			. "	</a>"
			. "</li>";
	}

	public function addTextMenuItem(string $title = "", string $icon = "far fa-circle")
	{
		return
			"<!-- TextMenuItem-->"
			. "<li class=\"nav-header\">$title</li>";
	}

	public function openCard(string $title = "")
	{
		return
			"<div class=\"card\">"
			. "  <div class=\"card-header\">"
			. "	<h3 class=\"card-title\">$title</h3>"
			. "  </div>"
			. "  <!-- /.card-header -->"
			. "  <div class=\"card-body\">";
	}
	public function closeCard()
	{
		return
			"</div>\n" .
			"<!-- /.card-body -->\n" .
			"</div>\n" .
			"<!-- /.card -->\n";
	}

	public function addCard(string $title = "", string $body = "")
	{
		return
			$this->openCard($title)
			. $body
			. $this->closeCard();
	}

	/**
	 * Opens a Bootstrap Modal window
	 * 
	 * @param string $baseid  id to start id properties 
	 * @param string $title title of modal window
	 * @param array $params default  [	"form-modal" => true, 
	 * 								  	"scrollable" => true, 
	 * 									"stackable" => true,
	 * 									"effects" => "fade",
	 * 									"modal-dialog-styles" => "modal-lg"]
	 */
	public function openModal(string $baseid = "default", string $title = "", array $params = [])
	{
		$defaultparams = [
			"form-modal" => true,
			"scrollable" => true,
			"stackable" => true,
			"effects" => "fade",
			"modal-dialog-styles" => "modal-lg"
		];

		$p = CustomHelper::array_merge_recursive_distinct($defaultparams, $params);

		return
			'<!-- MODAL ' . $baseid . ' -->'
			. (($p["form-modal"]) ?  '<form id="' . $baseid . '-modal-form">' : "")
			. '	<div class="modal ' . $p["effects"] . '" id="' . $baseid . '-modal" ' . (($p["stackable"]) ? 'data-backdrop="static"' : '') . ' tabindex="-1" role="dialog" aria-labelledby="' . $baseid . '-modal-title" aria-hidden="true">'
			. '		<div class="modal-dialog ' . (($p["scrollable"]) ? ' modal-dialog-scrollable ' : '') . $p["modal-dialog-styles"] . '" role="document">'
			. '			<div class="modal-content">'
			. '				<div class="modal-header">'
			. '					<h5 class="modal-title" id="' . $baseid . '-modal-title">' . $title . '</h5>'
			. '					<button type="button" class="close" data-dismiss="modal" aria-label="Close">'
			. '						<span aria-hidden="true">&times;</span>'
			. '					</button>'
			. '				</div>'
			. '				<div class="modal-body">';
	}


	/**
	 * Closes a Bootstrap Modal window
	 * 
	 * @param string $baseid	id to start id properties 
	 * @param array $buttons	buttons to be added to the modal
	 * 							default values "close" => ["label" => "Close", "tag" => "button", "class" => "btn btn-secondary", "data-dismiss" => "modal", "extra-properties" => ""] 
	*/
	public function closeModal(string $baseid = "default", array $buttons = [])
	{

		$defaultbuttons = [
			"close" => ["label" => "Close", "tag" => "button", "type" => "button", "class" => "btn-secondary", "data-dismiss" => "modal", "extra-properties" => ""],
		];

		$buttons = CustomHelper::array_merge_recursive_distinct($defaultbuttons, $buttons);

		$btn = "";
		if (count($buttons)) {
			foreach ($buttons as $key => $b) {
				if (count($b)) {
					$btn .= '<' . ($b["tag"] ?? 'button') . ' id="' . $baseid . '-modal-button-' . $key . '" type="' . ($b["type"] ?? 'button') . '" class="btn ' . ($b["class"] ?? 'btn-secondary') . '" data-dismiss="' . ($b["data-dismiss"] ?? 'modal') . '"  ' . ($b["extra-properties"] ?? '') . '  >' . ($b["label"] ?? 'Close') . '</' . ($b["tag"] ?? 'button') . '>';
				}
			}
		}
		return
			'			</div> <!-- ./modal-body -->'
			. '			<div class="modal-footer">'
			. $btn
			. '			</div>'
			. '		</div>'
			. '	</div>'
			. '	</div>'
			. '	</form>'
			. '	<!-- END MODAL ' . $baseid . ' --> '
			
			;
	}


	public function getSidebar(object $data)
	{
		$controller = $this;
		return
			$controller->addSidebarUserPanel($data->user_name, $data->user_avatar)

			//. $controller->addSidebarSearchForm()
			. $controller->addSidebarMenu("menu1", [
				$controller->addLinkMenuItem("opcao12", "Atendimentos", "#", "fas fa-comments"),
				//$controller->addTextMenuItem("CADASTROS", "far fa-circle"),
				$controller->addTreeViewMenuItem("opcao1", "BASES", "fas fa-database", false, [
					$controller->addLinkMenuItem("opcao11", "Agendamentos", "#", "far fa-calendar-check"),
					$controller->addLinkMenuItem("opcao12", "Clientes", "/admin/customer", "fas fa-user"),
					$controller->addLinkMenuItem("opcao13", "Equipamentos de Clientes", "/admin/qrobject", "fas fa-qrcode"),
					$controller->addLinkMenuItem("opcao14", "Técnicos", "#", "fas fa-user-cog"),
					$controller->addLinkMenuItem("opcao15", "Atendentes", "#", "fas fa-headset"),
				]),
				$controller->addTreeViewMenuItem("opcao2", "FERRAMENTAS", "fas fa-tools", false, [
					$controller->addLinkMenuItem("opcao21", "Gerar Etiquetas", "/admin/qrlabels", "fas fa-qrcode"),
				]),
			]);
	}


	public function view(string $view, $data = null) : string
	{

		$company_avatar = "/images/avatar/company.png";

		
		$sidebarinfo = new stdClass();

		$user = null;

		if (isset($_SESSION["auth"]["admin"]["local"]["user"])) {
			$user = $_SESSION["auth"]["admin"]["local"]["user"];
			$sidebarinfo->user = $user;
			$sidebarinfo->user_name = $user->worker->worker_name;
			$sidebarinfo->user_avatar = $user->worker->worker_avatar;
		} else {
			$sidebarinfo->user = null;
			$sidebarinfo->user_name = "Not identified";
			$sidebarinfo->user_avatar = "/images/avatar/default.png";
		} 



		$default_before_sidebar = $this->addSidebarCircleImageLink("AIRCON", $company_avatar);
		$default_sidebar = $this->getSidebar($sidebarinfo);
		
		$defaultData = [
			"layout" => "layouts/layout_adminlte",
			"view" => $this,
			"before_sidebar" => $default_before_sidebar,
			"sidebar" => $default_sidebar,
			"user" => $user,
		];

		$mergedData = CustomHelper::array_merge_recursive_distinct($defaultData, $data);
		$this->data = CustomHelper::array_merge_recursive_distinct($this->data, $mergedData);

		return $this->render($view);
	}



}