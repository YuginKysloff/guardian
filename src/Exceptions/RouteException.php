<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class RouteException extends AuthorizationException
{
    protected $permission;
    protected $model_type;
    protected $model_id_placeholder;

    /**
     * Create a RouteException instance.
     *
     * @param string $permission Permission name or action.
     * @param string $model_type Model name on which the permission is attached to.
     * @param string $model_id_placeholder Model ID on which the permission is attached to.
     * @return void
     */
    public function __construct($permission, $model_type, $model_id_placeholder)
    {
        $this->permission = $permission;
        $this->model_type = $model_type;
        $this->model_id_placeholder = $model_id_placeholder;

        parent::__construct('The parameter '.$model_id_placeholder.' passed to the Guardian middleware does not exist in the route.');
    }

    /**
     * Get the permission or action name.
     *
     * @return string Permission or action name.
     */
    public function permission()
    {
        return $this->permission;
    }

    /**
     * Get the placeholder name in the route where the ID will be put.
     *
     * @return string Model ID placeholder name.
     */
    public function modelIdPlaceholder()
    {
        return $this->model_id_placeholder;
    }

    /**
     * Get the Model class name.
     *
     * @return string Model class name.
     */
    public function modelType()
    {
        return $this->model_type;
    }
}
