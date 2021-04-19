<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "Salons",
            "Comptes",
            "Users",
            "Types abonnement",
            "Offres SMS",
            "SMS",
            "Admins",
        ];

        foreach ($permissions as $permission)
        {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
