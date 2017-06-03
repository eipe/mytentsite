<?php

namespace App\Http\Controllers;

use App\Models\Like;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{

    use RestControllerTrait;

    /**
     * Set like to opposite of the current state
     *
     * @param $id
     * @return mixed
     */
    public function handleLike($id)
    {
        $existing_like = Like::withTrashed()->whereTentSitesId($id)->whereUserId(Auth::id())->first();

        if (is_null($existing_like)) {
            $existing_like = Like::create([
                'user_id'       => Auth::id(),
                'tent_sites_id' => $id
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }

        $existing_like->total = Like::all()->where('tent_sites_id', $id)->count();
        return $existing_like;
    }

    /**
     * Get all
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLikesByUser($id) {
        $likes = Like::all()->where('user_id', $id);
        return $this->showResponse($likes);
    }
}
