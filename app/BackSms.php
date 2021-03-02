<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BackSms extends Model
{
    protected $table = "backsms";

    protected $fillable = [
        "to",
        "message",
        "user",
    ];
}
