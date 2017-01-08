<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Monolog\Handler\SlackHandler;
use Monolog\Logger;

class MonologSlackProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Push errors to slack channel
        $monolog = \Log::getMonolog();
        $slackHandler = new SlackHandler(env('SLACK_MONOLOG_TOKEN'), '#log', 'Monolog', true, null, Logger::ERROR);
        $monolog->pushHandler($slackHandler);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
