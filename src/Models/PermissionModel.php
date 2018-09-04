<?php

namespace Rennokki\Guardian\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $table = 'permissions';
    protected $guarded = [];
    protected $casts = [
        'is_prohibited' => 'bool',
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
