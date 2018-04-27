<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'tags';

    protected $fillable = [
        'tag_id',
        'name',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['count'];


    /**
     * The tags that belongs to tentsites
     */
    public function tentsites()
    {
        return $this->belongsToMany('App\Models\Tentsites', 'tentsite_tags',
            'tag_id', 'tent_site_id');
    }

    public static function saveTentSiteTags(TentSites $tentsite, $tags){
        // Loop and save
        if(is_array($tags) && !empty($tags)) {
            $tentsite->tags()->sync(array_values($tags));
        }

    }

    public function getCountAttribute()
    {
        return $this->tentsites()->count();
    }

}
