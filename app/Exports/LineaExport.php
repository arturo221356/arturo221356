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
            isset($linea->user) ? $linea->user->name: null,
            isset($linea->product->name) ? $linea->product->name : null,
            isset($linea->subProduct->name) ? $linea->subProduct->name : null,
            isset($linea->productoable->trafico_real) ? $linea->productoable->trafico_real : null,
            $linea->status,
            isset($linea->productoable->preactivated_at) ? Date::stringToExcel($linea->productoable->preactivated_at) : null,
            isset($linea->productoable->activated_at) ? Date::stringToExcel($linea->productoable->activated_at) : null,
            isset($linea->productoable->transaction->monto) ? $linea->productoable->transaction->monto : null,
            isset($linea->productoable->transaction->taecel_message) ? $linea->productoable->transaction->taecel_message : null,
            isset($linea->productoable->transaction->created_at) ?  Date::stringToExcel($linea->productoable->transaction->created_at) : null,
            isset($linea->comisiones->porta) ? $linea->comisiones->porta : 0,
            isset($linea->comisiones->n) ? $linea->comisiones->n : 0,
            isset($linea->comisiones->n1) ? $linea->comisiones->n1 : 0,
            isset($linea->comisiones->n2) ? $linea->comisiones->n2 : 0,
            isset($linea->comisiones->n3) ? $linea->comisiones->n3 : 0,
            isset($linea->comisiones->n4) ? $linea->comisiones->n4 : 0,
            isset($linea->comisiones->n5) ? $linea->comisiones->n5 : 0,
            isset($linea->comisiones->n6) ? $linea->comisiones->n6 : 0,
            isset($linea->comisiones->n7) ? $linea->comisiones->n7 : 0,
            isset($linea->comisiones->n8) ? $linea->comisiones->n8 : 0,
            isset($linea->comisiones->n9) ? $linea->comisiones->n9 : 0,
            isset($linea->comisiones->n10) ? $linea->comisiones->n10 : 0,
            isset($linea->comisiones->n11) ? $linea->comisiones->n11 : 0,


           
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
            'Usuario',
            'Producto',
            'Sub Producto',
            'Trafico',
            'Estatus',
            'Fecha Preactivacion',
            'Fecha de Activacion',
            'Monto Recarga',
            'Mensaje Recarga',
            'Fecha Recarga',
            'Comision Porta',
            'N 30',
            'N1 60',
            'N2 90',
            'N3 120',
            'N4/B P200',
            'N5/B ESP',
            'N6/ CHIP',
            'VOL 6',
            'VOL9',
            'VOL12',
            'CER',

        ];
    }
    public function columnFormats(): array
    {
        return [

            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
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
