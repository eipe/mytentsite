<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentSites extends Model
{

    const DB = 'tent_sites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reported_by',
        'latitude',
        'longitude',
        'location_name',
        'created_time',
        'likes',
        'img_location',
        'external_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}