<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLogin extends Model
{
    protected $table = 'social_login';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}