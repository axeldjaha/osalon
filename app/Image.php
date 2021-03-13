<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        "nom",
        "lien_id",
    ];

    public function lien()
    {
        return $this->belongsTo(Lien::class);
    }
}
