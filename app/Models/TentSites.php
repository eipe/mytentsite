<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class TentSites extends Model
{
    use Notifiable;
    use SoftDeletes;

    const DB = 'tent_sites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'latitude',
        'longitude',
        'caption',
        'taken_date',
        'approved'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['likes', 'reported_by_name', 'tags'];

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
       return env('SLACK_NOTIFICATION_ROUTE');
    }

    public function reportedBy() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    public function reportedByUser() {
        return $this->hasOne('App\Models\User', 'id', 'reported_by')->first();
    }

    public function getReportedByNameAttribute() {
        return $this->reportedByUser()->name;
    }

    public function getCaptionAttribute($caption)
    {
        return htmlspecialchars($caption);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }

    public function getTakenDateAttribute($date)
    {
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }

    public function getImgLocationAttribute($imageName)
    {
        return '/storage'.env('TENT_SITE_PHOTO_DIR').$imageName;
    }

    public function getThumbnailLocationAttribute($imageName)
    {
        return '/storage'.env('TENT_SITE_THUMBNAIL_DIR').$imageName;
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like')->whereDeletedAt(null)->pluck('user_id');
    }

    /**
     * The tags that belongs to tentsites
     */
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag', 'tentsite_tags',
            'tent_site_id', 'tag_id')->pluck('name');
    }

    public function getUserHasLiked()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();
        return (!is_null($like)) ? true : false;
    }

    public function getLikesAttribute()
    {
        return $this->likes();
    }

    public function getTagsAttribute()
    {
        return $this->tags();
    }

}