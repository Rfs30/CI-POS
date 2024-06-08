<?php

namespace App\Controllers;

class Layout extends BaseController
{
    public function index(): string
    {
        return view('layout/home');
    }
}
