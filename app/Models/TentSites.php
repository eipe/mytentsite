<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TentSites extends Model
{
    use Notifiable;

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
    protected $appends = ['likes'];

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
       return env('§SLACK_NOTIFICATION_ROUTE');
    }

    public function reportedBy() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    /**
     * Get the user's name.
     *
     * @param  string  $id
     * @return string
     */
    public function getReportedByAttribute($id)
    {
        $user = \DB::table('users')->where('id', $id)->first();
        if(is_object($user)) {
            return $user->name;
        }
        return $id;

    }

    public function getCaptionAttribute($caption)
    {
        return htmlspecialchars($caption);
    }

    public function getCreatedAtAttribute($date)
    {
        $formatDate = new \DateTime($date);
        return $formatDate->format('d. M. Y');
    }

    public function getTakenDateAttribute($date)
    {
        $formatDate = new \DateTime($date);
        return $formatDate->format('d. M. Y');
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
        return $this->hasMany('App\Models\Like')->whereDeletedAt(null)->count();
    }

    public function getUserHasLiked()
    {
        $like = $this->likes()->whereUserId(Auth::id())->first();
        return (!is_null($like)) ? true : false;
    }

    public function getLikesAttribute() {
        return $this->likes();
    }
}