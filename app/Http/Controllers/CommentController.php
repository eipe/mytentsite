<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    use RestControllerTrait;

    /* @var Comment MODEL */
    const MODEL = 'App\Models\Comment';
    protected $validationRules = ['comment' => 'required'];


    public function index($tentSiteId)
    {
        $m = self::MODEL;
        return $this->listResponse($m::all()->where('tent_site_id', $tentSiteId));
    }

    public function store(Request $request, $tentSiteId)
    {
        /**
         * @var Comment $m
         */
        $m = self::MODEL;
        try {
            // Receive file
            /**
             * @var \Validator $v
             */
            $v = \Validator::make($request->all(), $this->validationRules);
            if ($v->fails()) {
                throw new \Exception("ValidationException");
            }

            // Save data
            $post = $request->all();

            // We don't want to store the api token
            unset($post['api_token']);
            $post['user_id'] = Auth::user()->getAttribute('id');
            $post['tent_site_id'] = $tentSiteId;

            $data = $m::create($post);

            // Fire event
           // event(new NewCommentRegistered($data));
            return $this->createdResponse($data);
        } catch (\Exception $ex) {
            $data = ['form_validations' => $v->errors(), 'exception' => $ex->getMessage()];
            return $this->clientErrorResponse($data);
        }

    }

}
