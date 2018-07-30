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
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the permission exists in the database or not.
     */
    public function hasPermission($permission, $targetType = null, $targetId = null)
    {
        return (bool) ($this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
            ->count() == 1);
    }

    /**
     * Get the permission from the database. Returns null if it does not exist.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return PermissionModel The Permission model.
     */
    public function getPermission($permission, $targetType = null, $targetId = null)
    {
        return $this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $targetType)
            ->where('target_id', $targetId)
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
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model has a permission.
     */
    public function can($permission, $targetType = null, $targetId = null)
    {
        if (! $this->hasPermission($permission, $targetType, $targetId)) {
            if ($this->hasPermission($permission, $targetType)) {
                $permission = $this->getPermission($permission, $targetType);

                return (bool) ! $this->is_prohibited;
            }

            return false;
        }

        $permission = $this->getPermission($permission, $targetType, $targetId);

        return ! $permission->is_prohibited;
    }

    /**
     * Checks if the binded model has NOT a certain permission.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model has a permission.
     */
    public function cannot($permission, $targetType = null, $targetId = null)
    {
        return (bool) ! $this->can($permission, $targetType, $targetId);
    }

    /**
     * Alias to the cannot() method.
     */
    public function cant($permission, $targetType = null, $targetId = null)
    {
        return (bool) $this->cannot($permission, $targetType, $targetId);
    }

    /**
     * Allows the binded model a certain permission. It is prohibited, it is unprohibited.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model has gained the permission or not.
     */
    public function allow($permission, $targetType = null, $targetId = null)
    {
        if ($this->hasPermission($permission, $targetType, $targetId)) {
            return $this->unprohibit($permission, $targetType, $targetId);
        }

        $model = config('guardian.model');

        $this->permissions()->save(new $model([
            'permission_name' => $permission,
            'is_prohibited'   => false,
            'target_type'     => ($targetType) ?: null,
            'target_id'       => ($targetType && $targetId) ? $targetId : null,
        ]));

        return true;
    }

    /**
     * Disallows the binded model a certain permission. If it has not, it is allowed then prohibited.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model got the permission prohibited or a removed.
     */
    public function disallow($permission, $targetType = null, $targetId = null)
    {
        if (! $this->hasPermission($permission, $targetType, $targetId)) {
            $this->allow($permission, $targetType, $targetId);

            return $this->prohibit($permission, $targetType, $targetId);
        }

        return $this->prohibit($permission, $targetType, $targetId);
    }

    /**
     * Alias to the disallow().
     */
    public function deletePermission($permission, $targetType = null, $targetId = null)
    {
        return $this->disallow($permission, $targetType, $targetId);
    }

    /**
     * Prohibit a certain permission. If it does not exist, it is allowed then prohibited.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model got the permission prohibited or not.
     */
    public function prohibit($permission, $targetType = null, $targetId = null)
    {
        if (! $this->hasPermission($permission, $targetType, $targetId)) {
            $this->allow($permission, $targetType, $targetId);

            return $this->prohibit($permission, $targetType, $targetId);
        }

        return $this->getPermission($permission, $targetType, $targetId)->update([
            'is_prohibited' => true,
        ]);
    }

    /**
     * Unprohibit a certain permission. If it does not exist, it is allowed.
     *
     * @param $permission Permission name or action.
     * @param string $targetType Model name on which the permission is attached to.
     * @param string $targetId Model ID on which the permission is attached to.
     * @return bool Wether the binded model got the permission allowed/uprohibited or not.
     */
    public function unprohibit($permission, $targetType = null, $targetId = null)
    {
        if (! $this->hasPermission($permission, $targetType, $targetId)) {
            return $this->allow($permission, $targetType, $targetId);
        }

        return $this->getPermission($permission, $targetType, $targetId)->update([
            'is_prohibited' => false,
        ]);
    }
}
