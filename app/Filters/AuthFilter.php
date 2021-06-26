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

        if (isset($_SESSION["auth"][$arguments[0]]["connected"]) && $_SESSION["auth"][$arguments[0]]["connected"]) {
            if ($request->uri->getPath() == $arguments[0]  . '/login') {
                return redirect()->to("/" . $arguments[0]);
            } elseif ($request->uri->getPath() == $arguments[0]  . '/logout') {
                unset($_SESSION["auth"][$arguments[0]]);
                $_SESSION["auth"][$arguments[0]]["connected"]=false;
                return redirect()->to("/" . $arguments[0] . '/login');
            }

            // if ($request->uri->getSegment(1) == 'admin')
            // {
            //      return redirect()->back();
            // }
        } else {
            if ($request->uri->getPath() != $arguments[0] . '/login') {
                return redirect()->to("/" . $arguments[0] . '/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
