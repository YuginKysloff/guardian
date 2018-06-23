<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class RouteException extends AuthorizationException
{
    protected $permission;
    protected $model_type;
    protected $model_id_placeholder;

    public function __construct($permission, $model_type, $model_id_placeholder)
    {
        $this->permission = $permission;
        $this->model_type = $model_type;
        $this->model_id_placeholder = $model_id_placeholder;

        parent::__construct('The parameter '.$model_id_placeholder.' passed to the Guardian middleware does not exist in the route.');
    }

    public function permission()
    {
        return $this->permission;
    }

    public function modelIdPlaceholder()
    {
        return $this->model_id_placeholder;
    }

    public function modelType()
    {
        return $this->model_type;
    }
}
