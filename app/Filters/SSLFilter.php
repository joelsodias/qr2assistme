<?php

//app/Filters/SSLFilter.php
namespace App\Filters;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class SSLFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            return redirect()->to("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
       
    }

} 