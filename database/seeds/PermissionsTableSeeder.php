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
            "Admins",
            "Pressings",
            "Users",
            "Abonnements",
            "Transactions",
            "Offres",
            "Prospects",
            "SMS",
        ];

        foreach ($permissions as $permission)
        {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
