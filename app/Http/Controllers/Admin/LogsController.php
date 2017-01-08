<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogsController extends Controller
{
    //
    public function index() {
        $data = [];

        return view('admin.logs', $data);
    }


}
