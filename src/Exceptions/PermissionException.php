<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class PermissionException extends AuthorizationException
{
    protected $permission;
    protected $model_type;
    protected $model_id_placeholder;

    /**
     * Create a PermissionException instance.
     *
     * @param string $permission Permission name or action.
     * @param string $model_type Model name on which the permission is attached to.
     * @param string $model_id_placeholder Model ID on which the permission is attached to.
     * @return void
     */
    public function __construct($permission, $model_type = null, $model_id_placeholder = null)
    {
        $message = 'Not enough permissions.';

        if ($model_type && ! $model_id_placeholder) {
            $message = 'Not enough permissions on '.$model_type;
        }

        if ($model_type && $model_id_placeholder) {
            $message = 'Not enough permissions on '.$model_type.' with ID passed in '.$model_id_placeholder;
        }

        parent::__construct($message);

        $this->permission = $permission;
        $this->model_type = $model_type;
        $this->model_id_placeholder = $model_id_placeholder;
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
