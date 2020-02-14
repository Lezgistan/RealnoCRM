<?php

namespace App\Http\Middleware;

use Closure;

class UpdateUserActivity
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
        if (\Auth::check()){
            \DB::table('users')
                ->where('id', \Auth::id())
                ->update(['last_active' => \Carbon\Carbon::now()]);
        }
        return $next($request);
    }
}
