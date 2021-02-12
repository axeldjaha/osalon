<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * Désigne un utilisateur standard. Par exemple un gérant
     * @var int
     */
    public static $ROLE_GERANT = 1;

    /**
     * Désigne un superviseur.
     * @var int
     */
    public static $ROLE_SUPERVISEUR = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'telephone',
        'email',
        "role",
        "activated",
        "password",
    ];

    /**
     * Un user peut être associé à plusieurs salons
     *
     * @return BelongsToMany
     */
    public function salons()
    {
        return $this->belongsToMany(Salon::class);
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
     * @return string
     */
    public static function generatePassword()
    {
        $numbers = '123456789';
        $part1 = substr(str_shuffle($numbers), 0, 3);
        $letters = 'abcdefghklmnpqrstuvwxyz';
        $part2 = substr(str_shuffle($letters), 0, 1);
        $password = Str::upper($part1 . $part2);

        return $password;
    }

}
