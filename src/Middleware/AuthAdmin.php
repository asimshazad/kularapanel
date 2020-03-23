<?php

namespace Khludev\KuLaraPanel\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class AuthAdmin extends Authenticate
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('admin.login');
        }
    }
}
