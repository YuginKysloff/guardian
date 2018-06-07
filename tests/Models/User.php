<?php

namespace Rennokki\Guardian\Test\Models;

use Illuminate\Database\Eloquent\Model;

use Rennokki\Guardian\Traits\HasPermissions;

class User extends Model
{
    use HasPermissions;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
