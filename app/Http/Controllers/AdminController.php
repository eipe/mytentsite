<?php

namespace App\Http\Controllers;

use App\Models\TentSites;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index() {

        $tentSites = new TentSitesController();

        $unapproved = $tentSites->getUnapproved();
        $data = [
            'tentsites' => $unapproved,
            'tentsites_count' => $unapproved->count(),
        ];
        return view('admin.dashboard', $data);
    }


}
