<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class PermissionException extends AuthorizationException
{

    protected $permission;
    protected $model_type;
    protected $model_id;

    public function __construct($permission, $model_type = null, $model_id = null)
    {
        $message = 'Not enough permissions.';

        if($model_type && !$model_id)
            $message = 'Not enough permissions on '.$model_type;

        if($model_type && $model_id)
            $message = 'Not enough permissions on '.$model_type.' with ID '.$model_id;

        parent::__construct($message);

        $this->permission = $permission;
        $this->model_type = $model_type;
        $this->model_id = $model_id;
    }

    public function permission()
    {
        return $this->permission;
    }

    public function modelId()
    {
        return $this->model_id;
    }

    public function modelType()
    {
        return $this->model_type;
    }
}