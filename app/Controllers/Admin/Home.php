<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        return view('admin/welcome_message');
    }
}
