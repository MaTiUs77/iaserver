<?php

namespace IAServer\Http\Middleware;

use Closure;
use IAServer\Http\Controllers\IAServer\DebugPro;

class ResponseLog
{
    public function handle($request, Closure $next,$filename)
    {
        $request->debugPro = new DebugPro($this,$filename);
        return $next($request);
    }

    public function terminate($request, $response){
        $request->debugPro->runtime();
    }
}
