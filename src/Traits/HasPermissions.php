<?php

namespace Rennokki\Guardian\Traits;

trait HasPermissions
{
    public function permissions()
    {
        return $this->morphMany(config('guardian.model'), 'model');
    }

    public function hasPermission($permission, $target_type = null, $target_id = null)
    {
        return (bool) ($this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $target_type)
            ->where('target_id', $target_id)
            ->count() == 1);
    }

    public function getPermission($permission, $target_type = null, $target_id = null)
    {
        return $this->permissions()
            ->where('permission_name', $permission)
            ->where('target_type', $target_type)
            ->where('target_id', $target_id)
            ->first();
    }

    public function allowedPermissions()
    {
        return $this->permissions()->where('is_prohibited', 0);
    }

    public function prohibitedPermissions()
    {
        return $this->permissions()->where('is_prohibited', 1);
    }

    public function can($permission, $target_type = null, $target_id = null)
    {
        if (!$this->hasPermission($permission, $target_type, $target_id)) {
            if ($this->hasPermission($permission, $target_type)) {
                $permission = $this->getPermission($permission, $target_type);

                return (bool) !$this->is_prohibited;
            }

            return false;
        }

        $permission = $this->getPermission($permission, $target_type, $target_id);

        return !$permission->is_prohibited;
    }

    public function cannot($permission, $target_type = null, $target_id = null)
    {
        return (bool) !$this->can($permission, $target_type, $target_id);
    }

    public function cant($permission, $target_type = null, $target_id = null)
    {
        return (bool) $this->cannot($permission, $target_type, $target_id);
    }

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

    public function disallow($permission, $target_type = null, $target_id = null, $prohibitInsteadOfDelete = false)
    {
        if (!$this->hasPermission($permission, $target_type, $target_id)) {
            $this->allow($permission, $target_type, $target_id);

            return $this->prohibit($permission, $target_type, $target_id);
        }

        if ($prohibitInsteadOfDelete) {
            return $this->prohibit($permission, $target_type, $target_id);
        }

        return (bool) $this->permissions()->where('permission_name', $permission)->delete();
    }

    public function deletePermission($permission, $target_type = null, $target_id = null)
    {
        return $this->disallow($permission, $target_type, $target_id);
    }

    public function prohibit($permission, $target_type = null, $target_id = null)
    {
        if (!$this->hasPermission($permission, $target_type, $target_id)) {
            $this->allow($permission, $target_type, $target_id);

            return $this->prohibit($permission, $target_type, $target_id);
        }

        return $this->getPermission($permission, $target_type, $target_id)->update([
            'is_prohibited' => true,
        ]);
    }

    public function unprohibit($permission, $target_type = null, $target_id = null)
    {
        if (!$this->hasPermission($permission, $target_type, $target_id)) {
            return $this->allow($permission, $target_type, $target_id);
        }

        return $this->getPermission($permission, $target_type, $target_id)->update([
            'is_prohibited' => false,
        ]);
    }
}
