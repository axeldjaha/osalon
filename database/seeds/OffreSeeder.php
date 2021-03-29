<?php

use App\Offre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table("offres")->count() == 0)
        {
            Offre::create([
                "intitule" => "Abonnement mensuel",
                "montant" => 4900,
            ]);
        }
    }
}
