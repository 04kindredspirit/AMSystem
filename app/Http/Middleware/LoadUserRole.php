<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoadUserRole
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            Auth::user()->load('roleDetails');
        }
        return $next($request);
    }
}