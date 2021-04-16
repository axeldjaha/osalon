<?php

use App\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeAbonnementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!Type::where("intitule", Type::$TYPE_MENSUEL)->exists())
        {
            Type::create([
                "intitule" => Type::$TYPE_MENSUEL,
                "montant" => Type::$MONTANT_MENSUEL,
            ]);
        }

        if(!Type::where("intitule", Type::$TYPE_ANNUEL)->exists())
        {
            Type::create([
                "intitule" => Type::$TYPE_ANNUEL,
                "montant" => Type::$MONTANT_ANNUEL,
            ]);
        }
    }
}
