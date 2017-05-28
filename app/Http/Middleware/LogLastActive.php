<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\AuthenticateController;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class LogLastActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        return $next($request);
    }

    public function terminate($request, $response)
    {
        // Store the session data...
        $user = AuthenticateController::getAuthenticatedUser();


        \DB::table('users')
            ->where('id', $user->id)
            ->update(['last_active' => Carbon::now()]);
        
        \Log::info($user->id);
    }
}