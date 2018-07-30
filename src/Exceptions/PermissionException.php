<?php

namespace Rennokki\Guardian\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class PermissionException extends AuthorizationException
{
    protected $permission;
    protected $modelType;
    protected $modelIdPlaceholder;

    /**
     * Create a PermissionException instance.
     *
     * @param $permission Permission name or action.
     * @param string $modelType Model name on which the permission is attached to.
     * @param string $modelIdPlaceholder Model ID on which the permission is attached to.
     * @return void
     */
    public function __construct($permission, $modelType = null, $modelIdPlaceholder = null)
    {
        $message = 'Not enough permissions.';

        if ($modelType && ! $modelIdPlaceholder) {
            $message = 'Not enough permissions on '.$modelType;
        }

        if ($modelType && $modelIdPlaceholder) {
            $message = 'Not enough permissions on '.$modelType.' with ID passed in '.$modelIdPlaceholder;
        }

        parent::__construct($message);

        $this->permission = $permission;
        $this->model_type = $modelType;
        $this->model_id_placeholder = $modelIdPlaceholder;
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
