<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StagingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Beta Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the beta registration site where users with a
    | delegated link can use a delegated code to access registration scheme
    |
    */
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForm()
    {
        return view('staging');
    }

    public function handle(Request $request)
    {
        $validator = Validator::make($request->all(), ['code' => 'regex:(glittertind)']);
        if($validator->fails())
        {
            return \Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }
        return redirect('/');
    }

}
