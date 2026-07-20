<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{

    protected function redirectTo($request)
    {
        return null;
    }

    // protected function redirectTo($request): ?string
    // {
    //     if ($request->expectsJson() || $request->is('api/*')) {
    //         return null;
    //     }
    //     return route('login');
    // }

}
