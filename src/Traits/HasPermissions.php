<?php

namespace Rennokki\Guardian\Traits;

trait HasPermissions
{
    /**
     * Permissions relationship.
     *
     * @return morphMany The relatinship.
     */
    public function permissions()
    {
        return $this->morphMany(config('guardian.model'), 'model');
    }

    /**
     * Check wether the user called has a permission or not (it doesn't have to be allowed or not, it has to be stored).
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the permission exists in the database or not.
     */
    public function hasPermission($permission, $target_type = null, $target_id = null)
    {
        return (bool) ($this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $target_type)
            ->where('target_id', $target_id)
            ->count() == 1);
    }

    /**
     * Get the permission from the database. Returns null if it does not exist.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return PermissionModel The Permission model.
     */
    public function getPermission($permission, $target_type = null, $target_id = null)
    {
        return $this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $target_type)
            ->where('target_id', $target_id)
            ->first();
    }

    /**
     * Relatinship over allowed permissions.
     *
     * @return morphMany The relatinship.
     */
    public function allowedPermissions()
    {
        return $this->permissions()->where('is_prohibited', 0);
    }

    /**
     * Relatinship over prohibited permissions.
     *
     * @return morphMany The relatinship.
     */
    public function prohibitedPermissions()
    {
        return $this->permissions()->where('is_prohibited', 1);
    }

    /**
     * Checks if the binded model has a certain permission.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the binded model has a permission.
     */
    public function can($permission, $target_type = null, $target_id = null)
    {
        if (! $this->hasPermission($permission, $target_type, $target_id)) {
            if ($this->hasPermission($permission, $target_type)) {
                $permission = $this->getPermission($permission, $target_type);

                return (bool) ! $this->is_prohibited;
            }

            return false;
        }

        $permission = $this->getPermission($permission, $target_type, $target_id);

        return ! $permission->is_prohibited;
    }

    /**
     * Checks if the binded model has NOT a certain permission.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the binded model has a permission.
     */
    public function cannot($permission, $target_type = null, $target_id = null)
    {
        return (bool) ! $this->can($permission, $target_type, $target_id);
    }

    /**
     * Alias to the cannot() method.
     */
    public function cant($permission, $target_type = null, $target_id = null)
    {
        return (bool) $this->cannot($permission, $target_type, $target_id);
    }

    /**
     * Allows the binded model a certain permission. It is prohibited, it is unprohibited.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the binded model has gained the permission or not.
     */
    public function allow($permission, $target_type = null, $target_id = null)
    {
        if ($this->hasPermission($permission, $target_type, $target_id)) {
            return $this->unprohibit($permission, $target_type, $target_id);
        }

        $model = config('guardian.model');

        return $this->permissions()->save(new $model([
                'permission_name' => $permission,
                'is_prohibited'   => false,
                'target_type'     => ($target_type) ?: null,
                'target_id'       => ($target_type && $target_id) ? $target_id : null,
            ]));
    }

    /**
     * Disallows the binded model a certain permission. If it has not, it is allowed then prohibited.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @param bool $prohibitInsteadOfDelete Wether it should be prohibited instead of deleted from the database. Defaults to deletion.
     * @return bool Wether the binded model got the permission prohibited or a removed.
     */
    public function disallow($permission, $target_type = null, $target_id = null, $prohibitInsteadOfDelete = false)
    {
        if (! $this->hasPermission($permission, $target_type, $target_id)) {
            $this->allow($permission, $target_type, $target_id);

            return $this->prohibit($permission, $target_type, $target_id);
        }

        if ($prohibitInsteadOfDelete) {
            return $this->prohibit($permission, $target_type, $target_id);
        }

        return (bool) $this->permissions()->where('permission_name', $permission)->delete();
    }

    /**
     * Alias to the disallow() method with $prohibitInsteadOfDelete set to true.
     */
    public function deletePermission($permission, $target_type = null, $target_id = null)
    {
        return $this->disallow($permission, $target_type, $target_id);
    }

    /**
     * Prohibit a certain permission. If it does not exist, it is allowed then prohibited.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the binded model got the permission prohibited or not.
     */
    public function prohibit($permission, $target_type = null, $target_id = null)
    {
        if (! $this->hasPermission($permission, $target_type, $target_id)) {
            $this->allow($permission, $target_type, $target_id);

            return $this->prohibit($permission, $target_type, $target_id);
        }

        return $this->getPermission($permission, $target_type, $target_id)->update([
            'is_prohibited' => true,
        ]);
    }

    /**
     * Unprohibit a certain permission. If it does not exist, it is allowed.
     *
     * @param string $permission Permission name or action.
     * @param string $target_type Model name on which the permission is attached to.
     * @param string $target_id Model ID on which the permission is attached to.
     * @return bool Wether the binded model got the permission allowed/uprohibited or not.
     */
    public function unprohibit($permission, $target_type = null, $target_id = null)
    {
        if (! $this->hasPermission($permission, $target_type, $target_id)) {
            return $this->allow($permission, $target_type, $target_id);
        }

        return $this->getPermission($permission, $target_type, $target_id)->update([
            'is_prohibited' => false,
        ]);
    }
}
