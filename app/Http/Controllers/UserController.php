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
        $user = DB::table($m::DB)->select('name', 'id', 'is_admin', 'language')->where('id', Auth::id())->first();
        if($user->is_admin)
        {
            $user->roles = array('admin');
        }
        return $this->showResponse($user);
    }


    public function getAllUsers()
    {
        $m = self::MODEL;
        return $this->listResponse(DB::table($m::DB)->select(
            'name', 'email', 'created_at', 'updated_at', 'is_admin', 'last_active'
        )->orderBy('name')->get());
    }


    public function updateLanguage(Request $request) {
        $m = self::MODEL;
        $user = $m::findOrFail(Auth::id(), ['id']);
        $user->language = $request->get('language');
        $user->save();

        return $this->showResponse($user);
    }

}