<?php

namespace Rennokki\Guardian\Middleware;

use Rennokki\Guardian\Exceptions\PermissionException;

use Closure;

class CheckPermission
{

    public function handle($request, Closure $next, $permission, $model_type = null, $model_id = null)
    {
        if(!$request->user())
            throw new PermissionException($permission, $model_type, $model_id);

        if($request->user()->cannot($permission, $model_type, $model_id))
            throw new PermissionException($permission, $model_type, $model_id);

        return $next($request);
    }

}
