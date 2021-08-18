<?php

namespace asimshazad\simplepanel\Middleware;

use Illuminate\Auth\Access\AuthorizationException;

class NotAdminRole
{
    public function handle($request, $next)
    {
        // check if role is admin
        if (app(config('asimshazad.models.role'))->where('id', $request->route()->parameter('id'))->where('admin', true)->first()) {
            throw new AuthorizationException();
        }

        return $next($request);
    }
}
