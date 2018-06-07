<?php

namespace Rennokki\Guardian\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'model_id', 'model_type',
        'target_id', 'target_type',
        'permission_name',
        'is_prohibited',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo();
    }
}
