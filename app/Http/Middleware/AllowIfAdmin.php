<?php

namespace App\Http\Middleware;

use Closure;

class AllowIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::user()->isAdmin() !== true) {
            abort(403, 'You are not authorized for this information');
        }
        return $next($request);
    }
}
