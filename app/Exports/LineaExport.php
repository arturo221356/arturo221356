<?php

namespace App\Exports;

use App\Linea;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithMapping;

use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Maatwebsite\Excel\Concerns\WithStyles;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\WithColumnFormatting;



use Illuminate\Support\Carbon;

class LineaExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithColumnFormatting 
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $lineas;

    public function __construct($lineas)
    {

        $this->lineas = $lineas;
    }
    public function map($linea): array
    {
        return [


            $linea->icc->icc . "F",
            $linea->dn,
            $linea->icc->company->name,
            $linea->icc->type->name,
            $linea->icc->inventario->inventarioable->name,
            isset($linea->product->name) ? $linea->product->name : null,
            isset($linea->productoable->trafico_real) ? $linea->productoable->trafico_real : null,
            $linea->status,
            isset($this->linea->status->reason) ? $this->linea->status->reason: null,
            isset($linea->productoable->preactivated_at) ? Date::stringToExcel($linea->productoable->preactivated_at) : null,
            isset($linea->productoable->activated_at) ? Date::stringToExcel($linea->productoable->activated_at) : null,
            isset($linea->productoable->transaction->monto) ? $linea->productoable->transaction->monto : null,
            isset($linea->productoable->transaction->taecel_message) ? $linea->productoable->transaction->taecel_message : null,
            isset($linea->productoable->transaction->created_at) ?  Date::stringToExcel($linea->productoable->transaction->created_at) : null,


           
        ];
    }
    public function headings(): array
    {
        return [
            'Icc',
            'Numero',
            'Compañia',
            'Tipo',
            'Inventario',
            'Producto',
            'Trafico',
            'Estatus',
            '',
            'Fecha Preactivacion',
            'Fecha de Activacion',
            'Monto Recarga',
            'Mensaje Recarga',
            'Fecha Recarga'
        ];
    }
    public function columnFormats(): array
    {
        return [

            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true,'size' => 14,'color' => array('rgb' => '000000')]],


        ];
    }


    public function collection()
    {
        return $this->lineas;
    }
}
