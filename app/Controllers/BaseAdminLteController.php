<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Services as AppServices;
use Ramsey\Uuid\Nonstandard\UuidV6;
use chillerlan\QRCode\QRCode;
use App\Helpers\CustomHelper;
use App\Views\BaseAdminLteView;

class BaseAdminLteController extends BaseController
{
    protected $viewClass = "App\Views\BaseAdminLteView";
}
