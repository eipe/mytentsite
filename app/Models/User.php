<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const DB = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function socialLogin()
    {
        return $this->hasMany('App\Models\SocialLogin');
    }

    public function tentSites()
    {
        return $this->hasMany('App\Models\TentSites', 'reported_by');
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return env('§SLACK_NOTIFICATION_ROUTE');
    }

    public function isAdmin() {
        return (bool) $this->getAttribute('is_admin');
    }

    public function getLikedTentSites()
    {
        return $this->hasMany('App\Models\Like')->whereDeletedAt(null);
    }
}
