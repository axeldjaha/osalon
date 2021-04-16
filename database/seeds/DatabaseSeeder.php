<?php

use App\Client;
use App\Paiement;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(SuperAdminRoleSeeder::class);
        $this->call(TypeAbonnementSeeder::class);

        //factory(Client::class, 100)->create();

    }
}
