<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Auth;

class AdminMiddleware
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
        if (! Auth::check()) {
            return redirect()->route('index.login');
        }

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            return redirect()->route('user.index');
        }

        return $next($request);
    }
}
