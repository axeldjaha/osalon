<?php

use App\Contact;
use App\SmsGroupe;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersSmsGroup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $smsGroupe = SmsGroupe::firstOrCreate(["intitule" => SmsGroupe::$USERS]);
        $smsGroupe->contacts()->delete();

        $data = [];
        $date = Carbon::now();
        $createdAt = $date;
        $updatedAt = $date;
        User::each(function ($user) use (&$data, $smsGroupe, $createdAt, $updatedAt)
        {
            $data[] = [
                "nom" => $user->name,
                "telephone" => $user->telephone,
                "sms_groupe_id" => $smsGroupe->id,
                $createdAt,
                $updatedAt,
            ];
        });

        $modelInstance = new Contact();
        $columns = [
            "nom",
            "telephone",
            "sms_groupe_id",
            "created_at",
            "updated_at",
        ];

        if(count($data) > 0)
        {
            $r = batch()->insert($modelInstance, $columns, $data);
            /*
             * Supprimer les doublons pour le groupe actuel
             */
            $query = "
            DELETE FROM contacts
            WHERE id NOT IN (
                SELECT *
                FROM (
                    SELECT MIN(id)
                    FROM contacts
                    WHERE sms_groupe_id = ?
                    GROUP BY contacts.telephone
                ) temp
            ) AND sms_groupe_id = ?";
            DB::delete($query, [$smsGroupe->id, $smsGroupe->id]);
        }
        else
        {
            $smsGroupe->delete();
        }
    }
}
