<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        return route('login');
    }

    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         return route('login');
    //     }
    // }
}
