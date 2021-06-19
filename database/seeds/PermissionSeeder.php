<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            "panier.store" => "Enregistrer encaissement",
            "panier.cancel" => "Annuler encaissement",
            "panier.delete" => "Supprimer encaissement",

            "caisse" => "Voir la caisse",

            "depense.store" => "Enregistrer dépense",
            "depense.cancel" => "Annuler dépense",
            "depense.delete" => "Supprimer dépense",

            "sms.store" => "Envoyer SMS",
            "sms.delete" => "Supprimer SMS",

            "user.manage" => "Gérer les utilisateurs",
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
