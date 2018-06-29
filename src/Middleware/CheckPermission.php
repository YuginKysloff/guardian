<?php

namespace Rennokki\Guardian\Middleware;

use Closure;
use Rennokki\Guardian\Exceptions\RouteException;
use Rennokki\Guardian\Exceptions\PermissionException;

class CheckPermission
{
    /**
     * Middlewares the current route for Guardian permissions.
     *
     * @param Request $request The request.
     * @param Closure $next Closure for passing the request to the next middleware.
     * @param string $permission Permission name or action.
     * @param string $model_type Model name on which the permission is attached to.
     * @param string $model_id_placeholder Model ID on which the permission is attached to.
     * @return Closure|PermissionException|RouteException Either Closure or exception.
     */
    public function handle($request, Closure $next, $permission, $model_type = null, $model_id_placeholder = null)
    {
        if (! $request->user()) {
            throw new PermissionException($permission, $model_type, $model_id_placeholder);
        }

        if ($model_id_placeholder && ! $request->route($model_id_placeholder)) {
            throw new RouteException($permission, $model_type, $model_id_placeholder);
        }

        if ($request->user()->cannot($permission, $model_type, $request->route($model_id_placeholder))) {
            throw new PermissionException($permission, $model_type, $model_id_placeholder);
        }

        return $next($request);
    }
}
