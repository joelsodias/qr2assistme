<?php

namespace App\Views;

use \CodeIgniter\View\View;
use App\Helpers\CustomHelper;
use Config\View as ViewConfig;
use CodeIgniter\Autoloader\FileLocator;
use Psr\Log\LoggerInterface;

class BaseView extends View
{

	protected $secutity;

	/**
	 * Constructor
	 *
	 * @param ViewConfig       $config
	 * @param string|null      $viewPath
	 * @param FileLocator|null $loader
	 * @param boolean|null     $debug
	 * @param LoggerInterface  $logger
	 */
	public function __construct(ViewConfig $config, string $viewPath = null, FileLocator $loader = null, bool $debug = null, LoggerInterface $logger = null)
	{
		parent::__construct($config, $viewPath, $loader, $debug, $logger);
		$this->security = \Config\Services::security();
	}

	public function getCustomHTML($data = null)
	{
		return "";
	}

	protected function getCSRFDefaultScript()
	{
?><script>
		var _csr = {
			tn: '<?= base64_encode($this->security->getCSRFTokenName()) ?>',
			th: '<?= base64_encode($this->security->getCSRFHash()) ?>',
			cn: '<?= base64_encode($this->security->getCookieName()) ?>',
			hn: '<?= base64_encode($this->security->getHeaderName()) ?>',
		}
	</script>
<?php
	}



	public function view(string $view, $data = null) : string
	{

		$defaultData = [
			"view" => $this,
		];

		$mergedData = CustomHelper::array_merge_recursive_distinct($defaultData, $data);
		$this->data = CustomHelper::array_merge_recursive_distinct($this->data, $mergedData);

		return $this->render($view);
	}



}
