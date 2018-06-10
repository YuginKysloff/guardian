<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class PermissionException extends AuthorizationException
{

    protected $permission;
    protected $model_type;
    protected $model_id_placeholder;

    public function __construct($permission, $model_type = null, $model_id_placeholder = null)
    {
        $message = 'Not enough permissions.';

        if($model_type && !$model_id_placeholder)
            $message = 'Not enough permissions on '.$model_type;

        if($model_type && $model_id_placeholder)
            $message = 'Not enough permissions on '.$model_type.' with ID passed in '.$model_id_placeholder;

        parent::__construct($message);

        $this->permission = $permission;
        $this->model_type = $model_type;
        $this->model_id_placeholder = $model_id_placeholder;
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