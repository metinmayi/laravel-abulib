<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale') && is_string(session()->get('locale'))) {
            app()->setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}
