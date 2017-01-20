<?php

namespace App\Http\Controllers;

use App\Models\Like;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    public function handleLike($id)
    {
        $existing_like = Like::withTrashed()->whereTentSitesId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            Like::create([
                'user_id'       => Auth::id(),
                'tent_sites_id'   => $id
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }
        return $existing_like;
    }
}
