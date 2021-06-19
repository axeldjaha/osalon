<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use jeremykenedy\LaravelLogger\App\Models\Activity;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles;

    public static $ROLE_PROPRIETAIRE = "proprietaire";
    public static $PERMISSION_MANAGE = "user.manage";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'telephone',
        'email',
        "compte_id",
        "password",
    ];

    public function compte()
    {
        return $this->belongsTo(Compte::class);
    }

    /**
     * Un user peut être associé à plusieurs salons
     *
     * @return BelongsToMany
     */
    public function salons()
    {
        return $this->belongsToMany(Salon::class);
    }

    public function logs()
    {
        return $this->hasMany(Activity::class, "userId");
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Generate a random password
     *
     * @param $telephone
     * @return string
     */
    public static function generatePassword($telephone)
    {
        //$numbers = '123456789';
        //$part1 = substr(str_shuffle($numbers), 0, 4);
        //$letters = 'abcdefghklmnpqrstuvwxyz';
        //$part2 = substr(str_shuffle($letters), 0, 1);
        //$password = Str::upper($part1 . $part2);
        //$password = Str::upper($part1);
        //return $password;

        /**
         * 1. x = les 4 derniers chiffres du numéro de téléphone
         * 2. si valeur(x) = 0, x = 1
         * 3. y = le quantième du mois
         * 4. z = x * y
         * 5. p = les 4 premiers chiffres de z
         * 6. si longueur(p) inférieur à 4, ajouter des zéro pour obtenir 4 chiffres
         * p est le mot de passe
         */
        $x = substr($telephone, -4); //1
        $x == 0 ? $x = 1 : $x; //2
        $y = date("d"); //3
        $z = $x * $y; //4
        $p = substr($z, 0, 4); //5
        if(strlen($p) < 4)
        {
            for ($i = strlen($p); $i < 4; $i++)
            {
                $p .= 0; //6
            }
        }

        return $p;
    }

}
