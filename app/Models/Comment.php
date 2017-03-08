<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'tent_site_id',
        'comment'
    ];

    public function userId() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    /**
     * Get the user's name.
     *
     * @param  string  $id
     * @return string
     */
    public function getUserIdAttribute($id)
    {
        $user = \DB::table('users')->where('id', $id)->first();
        if(is_object($user)) {
            return $user->name;
        }
        return $id;

    }


}
