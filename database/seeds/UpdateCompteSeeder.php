<?php

use Illuminate\Database\Seeder;

class UpdateCompteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Salon::each(function ($salon){
            $salon->compte->update([
                "pays_id" => $salon->pays_id,
            ]);
        });
    }
}
