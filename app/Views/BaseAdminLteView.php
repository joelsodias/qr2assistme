<?php

namespace App\Views;

use App\Helpers\CustomHelper;

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

		$avatar_company = "/images/avatar/company_logo.png";
		$avatar_user = "data:image/png;base64,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";

		$before_sidebar = $this->addSidebarCircleImageLink("AIRCON", $avatar_company);

		$sidebar = $this->getSidebar((object) array("user_name" => "Fulano", "user_avatar" => $avatar_user));


		$defaultData = [
			"layout" => "layouts/layout_adminlte",
			"view" => $this,
			"before_sidebar" => $before_sidebar,
			"sidebar" => $sidebar,
		];

		$mergedData = CustomHelper::array_merge_recursive_distinct($defaultData, $data);
		$this->data = CustomHelper::array_merge_recursive_distinct($this->data, $mergedData);

		return $this->render($view);
	}



}