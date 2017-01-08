<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LogsController extends Controller
{
    //
    public function index() {
        $data = [];
        return view('admin.logs', $data);
    }


}
