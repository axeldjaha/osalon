<?php


namespace App\Imports;


use App\Contact;
use App\SmsGroupe;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportSmsGroupe implements ToCollection, WithHeadingRow
{
    private $rows = 0;

    public function __construct()
    {

    }

    public function collection(Collection $rows)
    {
        $data = [];
        $header = $rows->first();
        $canImport = isset($header["telephone"]);
        if(!$canImport)
        {
            return;
        }

        $smsGroupe = SmsGroupe::create([
            "intitule" => request("intitule")
        ]);

        $date = Carbon::now();
        $createdAt = $date;
        $updatedAt = $date;
        foreach ($rows as $row)
        {
            $telephone = $this->formatPhoneNumber($row["telephone"]);
            if(Str::length($telephone) == 8 || Str::length($telephone) == 10)
            {
                $data[] = [
                    "nom" => $row["nom"] ?? null,
                    "telephone" => $telephone,
                    "sms_groupe_id" => $smsGroupe->id,
                    $createdAt,
                    $updatedAt,
                ];
            }
        }

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
            batch()->insert($modelInstance, $columns, $data);
            //Delete duplicated phone number for current groupe
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

        $this->rows = $smsGroupe->contacts->count();
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function formatPhoneNumber($telephone)
    {
        $phoneNumber = preg_replace('/[\-.\s]/s','', $telephone);
        $phoneNumber = substr($phoneNumber, 0, 10);
        return $phoneNumber;
    }

}
