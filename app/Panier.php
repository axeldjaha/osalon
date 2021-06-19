<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    public static $PERMISSION_STORE = "panier.store";
    public static $PERMISSION_CANCEL = "panier.cancel";
    public static $PERMISSION_DELETE = "panier.delete";

    protected $fillable = [
        "date",
        "salon_id",
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot(["canceled", "quantite"]);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot(["canceled", "prix"]);
    }

}
