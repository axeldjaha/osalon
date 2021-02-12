<?php

namespace App\Imports;

use App\FichierProspect;
use App\Prospect;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProspectsImport implements ToModel, WithHeadingRow
{
    private $fichier;

    public function __construct(FichierProspect $fichier)
    {
        $this->fichier = $fichier;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $telephones = explode("/", $row["telephone"]);

        if(count($telephones) > 1)
        {
            $date = Carbon::now();
            $createdAt = $date;
            $updatedAt = $date;
            $values = [];

            foreach ($telephones as $telephone)
            {
                $tel = $this->formatTelephone($telephone);
                if(Str::length($tel) == 8)
                {
                    $values[] = [
                        $row["nom"],
                        $tel,
                        $this->fichier->id,
                        $createdAt,
                        $updatedAt,
                    ];
                }
            }

            if(count($values) > 0)
            {
                $columns = [
                    "nom",
                    "telephone",
                    "fichier_prospect_id",
                    "created_at",
                    "updated_at",
                ];
                $modelInstance = new Prospect();
                batch()->insert($modelInstance, $columns, $values);
            }

            return null;
        }
        else
        {
            $telephone = $this->formatTelephone($row["telephone"]);
            if(Str::length($telephone) == 8)
            {
                return new Prospect([
                    "nom" => $row["nom"],
                    "telephone" => $telephone,
                    "fichier_prospect_id" => $this->fichier->id,
                ]);
            }
            else
            {
                return null;
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function chunkSize(): int
    {
        return 1000;
    }

    public function formatTelephone($telephone)
    {
        $phoneNumber = trim($telephone);
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace('-', '', $phoneNumber);
        $phoneNumber = str_replace('/', '', $phoneNumber);
        $phoneNumber = substr($phoneNumber, 0, 8);
        return Str::length($phoneNumber) < 8 ? '0'.$phoneNumber : $phoneNumber;
    }

}
