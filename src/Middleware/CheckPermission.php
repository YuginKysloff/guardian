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
     * @param $permission Permission name or action.
     * @param string $modelType Model name on which the permission is attached to.
     * @param string $modelIdPlaceholder Model ID on which the permission is attached to.
     * @return Closure|PermissionException|RouteException Either Closure or exception.
     */
    public function handle($request, Closure $next, $permission, $modelType = null, $modelIdPlaceholder = null)
    {
        if (! $request->user()) {
            throw new PermissionException($permission, $modelType, $modelIdPlaceholder);
        }

        if ($modelIdPlaceholder && ! $request->route($modelIdPlaceholder)) {
            throw new RouteException($permission, $modelType, $modelIdPlaceholder);
        }

        if ($request->user()->cannot($permission, $modelType, $request->route($modelIdPlaceholder))) {
            throw new PermissionException($permission, $modelType, $modelIdPlaceholder);
        }

        return $next($request);
    }
}
