<?php

namespace App\Providers;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\TentSitesController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $tentSites = new TentSitesController();
        $unapproved = $tentSites->getUnapproved();
        View::share('tentsites', $unapproved);
        View::share('tentsites_count', $unapproved->count());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
