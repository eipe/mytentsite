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
        'reported_by',
        'latitude',
        'longitude',
        'location_name',
        'created_at',
        'likes',
        'img_location',
        'external_id',
        'approved'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        if (\App::environment('production')) {
            return "https://hooks.slack.com/services/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";
        } else {
            return "https://hooks.slack.com/services/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";
        }
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
        return $user->name;
    }
}