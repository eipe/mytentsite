<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TentSitesController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index() {
        $status = json_decode($this->getStatusData());

        return view('admin.dashboard', ['status' => $status]);
    }

    public function getStatusData() {
        $client = new Client();
        $metrics = $client->request('GET', env('UPDOWN_URL').'/checks/wjjs/metrics?api-key='.env('UPDOWN_API_KEY'));
        return($metrics->getBody()->getContents());
    }
}
