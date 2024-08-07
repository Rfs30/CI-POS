<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class FilterKasir implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->idlevel == '') {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if (session()->idlevel == 2) {
            return redirect()->to('layout');
        }
    }
}
