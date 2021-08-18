<?php

namespace asimshazad\simplepanel\Middleware;

use Closure;

class ApiLogger
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
    	config(['filesystems.disks.local.root' => storage_path('logs/api')]);
        \Storage::append('log-'.date('Y-m').'.log', date('Y-m-d H:i:s')."\t:\t". json_encode([
        		'endpoint' => $request->url(),
        		'header' => $request->header(),
        		'ip' => $request->ip(),
	        	'request' => $request->all(),
	        ]));
    }
}
