<?php

use App\Depense;
use App\Panier;
use App\Sms;
use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::firstOrCreate([
            "name" => User::$ROLE_PROPRIETAIRE,
            'guard_name' => "api",
        ]);

        $permissions = [
            Panier::$PERMISSION_STORE => "Enregistrer encaissement",
            Panier::$PERMISSION_CANCEL => "Annuler encaissement",
            Panier::$PERMISSION_DELETE => "Supprimer encaissement",

            "caisse" => "Voir la caisse",

            Depense::$PERMISSION_STORE => "Enregistrer dépense",
            Depense::$PERMISSION_DELETE => "Supprimer dépense",

            Sms::$PERMISSION_STORE => "Envoyer SMS",
            Sms::$PERMISSION_DELETE => "Supprimer SMS",

            User::$PERMISSION_MANAGE => "Gérer les utilisateurs",
        ];

        foreach ($permissions as $name => $title)
        {
            Permission::firstOrCreate([
                'name' => $name,
                'title' => $title,
                'guard_name' => "api",
            ]);
        }
    }
}
