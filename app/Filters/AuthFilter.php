<?php

//app/Filters/Auth.php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();
        $path = $request->uri->getPath();

        if (isset($_SESSION["auth"][$arguments[0]]["connected"]) && $_SESSION["auth"][$arguments[0]]["connected"]) {
            if ($path == $arguments[0]  . '/login') {
                return redirect()->to("/" . $arguments[0]);
            } elseif (
                $path == $arguments[0]  . '/logout' ||
                str_ends_with($path, '/logout')
            ) {
                unset($_SESSION["auth"][$arguments[0]]);
                $_SESSION["auth"][$arguments[0]]["connected"] = false;
                return redirect()->to("/" . $arguments[0] . '/login');
            }
        } else {
            if ($path != $arguments[0] . '/login') {
                return redirect()->to("/" . $arguments[0] . '/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
