<?php

namespace Rennokki\Guardian\Test\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'content', 'author',
    ];
}
