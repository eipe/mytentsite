<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    use RestControllerTrait;

    /* @var User MODEL */
    const MODEL = 'App\Models\User';

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $m = self::MODEL;
        $user = DB::table($m::DB)->select('name', 'id')->where('id', Auth::id())->first();
        return $this->showResponse($user);
    }

}