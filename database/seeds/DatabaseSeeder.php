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
        $this->call(TypeAbonnementSeeder::class);
        $this->call(RoleAndPermissionSeeder::class);

        //factory(Client::class, 100)->create();

    }
}
