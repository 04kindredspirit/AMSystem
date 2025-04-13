<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Athenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}