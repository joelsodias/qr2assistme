<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Config\Services;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\IncomingRequest;
use Ramsey\Uuid\Nonstandard\UuidV6;
use App\Views\BaseView;
use Config\Services as AppServices;
use App\Helpers\CustomHelper;
use Ramsey\Uuid\Provider\Node\RandomNodeProvider;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */

class BaseController extends Controller
{
    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['cookie', 'html', 'text', 'url', 'custom'];

    protected $security;

    protected $internalView;

    protected $viewClass = "App\Views\BaseView";

    protected $userdata;

    /**
     * Constructor.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param LoggerInterface   $logger
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        //--------------------------------------------------------------------
        // Preload any models, libraries, etc, here.
        //--------------------------------------------------------------------
        // E.g.: $this->session = \Config\Services::session();
        $this->session = \Config\Services::session();
        $this->security = \Config\Services::security();

        if (class_exists($this->viewClass)) {
            $this->internalView = new $this->viewClass(
                config('View'),
                config('Paths')->viewDirectory,
                AppServices::locator(),
                CI_DEBUG,
                $logger
            );
        } else {
            $this->internalView = new BaseView(
                config('View'),
                config('Paths')->viewDirectory,
                AppServices::locator(),
                CI_DEBUG,
                $logger
            );
        }
    }


    protected function getNewUUid()
    {
        $nodeProvider = new RandomNodeProvider();
        return UuidV6::uuid6($nodeProvider->getNode());
    }

    protected function getNewUUidString()
    {
        return (string) $this->getNewUUid();
    }


    public function parseView($view, $data)
    {
        $parser = \Config\Services::parser();
        return $parser->setData($data)->render($view);
    }

    protected function getRequestParam($param_name)
    {
        $get = $this->getRequest()->getGet();
        $post = $this->getRequest()->getPost();
        $body = $this->getRequest()->getBody();
        $json = $this->getRequest()->getJSON();

        if (array_key_exists($param_name, $post ?? [])) {
            return $post[$param_name];
        } elseif (array_key_exists($param_name, $get ?? [])) {
            return $get[$param_name];
        } elseif (isset($json) && property_exists($json, $param_name)) {
            return $json->$param_name;
        } else {
            return null;
        }
    }

    protected function getRequestFiles()
    {
        return $this->getRequest()->getFiles();
    }



    protected function validateUuid($uuid = null, $allowNull = false)
    {
        return (($uuid == null && $allowNull) || (isset($uuid)) || UuidV6::isValid($uuid));
    }

    protected function getRequest(): IncomingRequest
    {
        return $this->request;
    }

    protected function getJsonWithCSRF($data)
    {
        if (isset($data)) {
            if (is_array($data)) {
                $data["_csr"]["n"] = base64_encode($this->security->getCSRFTokenName());
                $data["_csr"]["h"] = base64_encode($this->security->getCSRFHash());
            } elseif (is_object($data)) {
                $data->_csr["n"] = base64_encode($this->security->getCSRFTokenName());
                $data->_csr["h"] = base64_encode($this->security->getCSRFHash());
            } else {
            }
        } else {
            $data = [];
            $data["_csr"]["n"] = base64_encode($this->security->getCSRFTokenName());
            $data["_csr"]["h"] = base64_encode($this->security->getCSRFHash());
        }

        return $this->response->setJSON($data);
    }


    protected function view(string $view, $data = [])
    {
        $defaultData = [
            "controller" => $this,
        ];

        $mergedData = CustomHelper::array_merge_recursive_distinct($defaultData, $data);

        return $this->internalView->view($view, $mergedData);
    }


    protected function getCookieLoginInfo(string $key, string $section = null, string $provider = null)
    {
        $doGetCookie = function ($key) {
            $doUnserialize = function ($cookie) {
                $unserialized = @unserialize($cookie);
                if ($unserialized === 'b:0;' || $unserialized !== false) {
                    return $unserialized;
                } else {
                    return $cookie;
                }
            };


            $cookie = get_cookie($key);
            return $doUnserialize($cookie);
        };

        if (isset($section)) {
            if (isset($provider)) {
                return $doGetCookie('app_auth_' . $section . '_' . $provider . "_" . $key);
            } else {
                return $doGetCookie('app_auth_' . $section . '_' . $key);
            }
        } else {
            return $doGetCookie('app_auth_' . $key);
        }
    }

    protected function setCookieLoginInfo($value, string $section = null, string $provider = null, string $key = null)
    {
        $doSetCookie = function ($key, $value) {
            return cookie($key, serialize($value));
        };

        if (isset($section)) {
            if (isset($provider)) {
                if (isset($key)) {
                    return $doSetCookie('app_auth_' . $section . '_' . $provider . "_" . $key, $value);
                } else {
                    return $doSetCookie('app_auth_' . $section . '_' . $provider, $value);
                }
            } else {
                return $doSetCookie('app_auth_' . $section, $value);
            }
        } else {
            return $doSetCookie('app_auth', $value);
        }
    }

    protected function clearCookieLoginInfo(string $section = null, string $provider = null)
    {
        return $this->setCookieLoginInfo(null, $section, $provider);
    }

    protected function getSessionLoginInfo(string $key, string $section = null, string $provider = null)
    {
        if (isset($section)) {
            if (isset($provider)) {
                return $_SESSION["auth"][$section][$provider][$key] ?? null;
            } else {
                return $_SESSION["auth"][$section][$key] ?? null;
            }
        } else {
            return $_SESSION["auth"][$key] ?? null;
        }
    }

    protected function setSessionLoginInfo($value, string $section = null, string $provider = null, string $key = null)
    {
        if (isset($section)) {
            if (isset($provider)) {
                if (isset($key)) {
                    $_SESSION["auth"][$section][$provider][$key] = $value;
                } else {
                    $_SESSION["auth"][$section][$provider] = $value;
                }
            } else {
                $_SESSION["auth"][$section] = $value;
            }
        } else {
            $_SESSION["auth"] = $value;
        }
        return true;
    }

    public function checkSessionLoggedOn(string $section, string $provider)
    {
        if (isset($section)) {
            if (isset($provider)) {
                return $this->getSessionLoginInfo("connected", $section, $provider);
            }
        }

        return false;
    }

    protected function clearSessionLoginInfo(string $section = null, string $provider = null)
    {
        return $this->setSessionLoginInfo(null, $section, $provider);
    }
}
