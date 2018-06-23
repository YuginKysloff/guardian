<?php

namespace Rennokki\Guardian\Test\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Rennokki\Guardian\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasPermissions;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
