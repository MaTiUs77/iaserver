<?php

namespace IAServer\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user())
        {
            $role = explode('|',$role);

            if(!$request->user()->hasRole($role)) {
                $output = ['error'=> 'No tiene permisos necesarios para ejecutar esta aplicacion.'];
                return Response::multiple_output($output,'errors.msg');
            }
        } else {
            $output = ['error'=> 'No se encuentra autentificado.'];
            return Response::multiple_output($output,'errors.msg');
        }
        return $next($request);
    }
}
