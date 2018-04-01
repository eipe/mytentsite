<?php

namespace App\Models;

use Carbon\Carbon;
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

    protected $appends = [
        'user_name'
    ];

    public function userId() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    private function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id')->first();
    }

    public function getUserNameAttribute() {
        return $this->user()->name;
    }

    /**
     * Get the created at as human readable date
     *
     * @return string
     */
    public function getCreatedAtAttribute()
    {
        return Carbon::createFromTimeStamp(strtotime($this->attributes['created_at']) )->diffForHumans();
    }


}
