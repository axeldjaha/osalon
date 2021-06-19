<?php

use App\Compte;
use App\Depense;
use App\Panier;
use App\Sms;
use App\User;
use Illuminate\Database\Seeder;

class Update extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            Panier::$PERMISSION_STORE,
            Panier::$PERMISSION_CANCEL,
            Panier::$PERMISSION_DELETE,
            "caisse",
            Depense::$PERMISSION_STORE,
            Depense::$PERMISSION_CANCEL,
            Depense::$PERMISSION_DELETE,
            Sms::$PERMISSION_STORE,
            Sms::$PERMISSION_DELETE,
        ];

        Compte::each(function ($compte) use ($permissions){
            $users = $compte->users;
            for ($index = 0; $index < count($users); $index++)
            {
                if($index == 0)
                {
                    $users[$index]->assignRole(User::$ROLE_PROPRIETAIRE);
                }
                else
                {
                    $users[$index]->syncPermissions($permissions);
                }
            }
        });
    }
}
