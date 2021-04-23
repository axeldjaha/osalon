<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        "user_id",
        "created_at",
        "updated_at",
    ];
}
