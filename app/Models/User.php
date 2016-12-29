<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

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
        if (\App::environment('production')) {
            return "https://hooks.slack.com/services/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";
        } else {
            return "https://hooks.slack.com/services/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";
        }
    }

    public function isAdmin() {
        return (bool) $this->getAttribute('is_admin');
    }
}
