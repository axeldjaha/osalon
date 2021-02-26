<?php


namespace App\Exports;


use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSmsGroupe implements FromCollection, WithMapping, WithHeadings,
    ShouldAutoSize, WithColumnFormatting
{
    private $collection;
    private $columns;
    private $headings;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;

        $this->columns = [
            "nom",
            "telephone",
        ];

        $this->headings = [
            "nom" => "Nom",
            "telephone" => "Téléphone",
        ];
    }

    public function collection()
    {
        return $this->collection;
    }

    public function map($groupe): array
    {
        $data = [];
        foreach ($this->columns as $column)
        {
            $data[] = $groupe->{$column};
        }
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        $headings = [];
        foreach ($this->columns as $column)
        {
            $headings[] = $this->headings[$column];
        }
        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            //'A' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
