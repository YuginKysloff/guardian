<?php

namespace Rennokki\Guardian\Middleware;

use Rennokki\Guardian\Exceptions\PermissionException;
use Rennokki\Guardian\Exceptions\RouteException;

use Closure;

class CheckPermission
{

    public function handle($request, Closure $next, $permission, $model_type = null, $model_id_placeholder = null)
    {
        if (!$request->user())
            throw new PermissionException($permission, $model_type, $model_id_placeholder);

        if ($model_id_placeholder && !$request->route($model_id_placeholder))
            throw new RouteException($permission, $model_type, $model_id_placeholder);
        
        if ($request->user()->cannot($permission, $model_type, $request->route($model_id_placeholder)))
            throw new PermissionException($permission, $model_type, $model_id_placeholder);

        return $next($request);
    }

}
