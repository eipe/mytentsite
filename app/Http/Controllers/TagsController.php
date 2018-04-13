<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagsController extends Controller
{

    use RestControllerTrait;

    /* @var Tag MODEL */
    const MODEL = 'App\Models\Tag';


    public function index()
    {
        $m = self::MODEL;
        return $this->listResponse($m::all());
    }


}
