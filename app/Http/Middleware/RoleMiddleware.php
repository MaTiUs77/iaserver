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
                $output = [
                    'codigo'=> 401,
                    'error'=> 'No tiene los permisos necesarios para ingresar a esta ruta.'
                ];
                return Response::multiple($output,'errors.msg');
            }
        } else {
            $output = [
                'codigo'=> 401,
                'error'=> 'Debe iniciar session para ingresar a esta ruta.'
            ];
            return Response::multiple($output,'errors.msg');
        }
        return $next($request);
    }
}
