<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;

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
use Illuminate\Support\Facades\Auth as FacadesAuth;

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

        $response =  [


            $linea->icc->icc . "F",
            $linea->dn,
            $linea->icc->company->name,
            $linea->icc->type->name,
            $linea->icc->inventario->inventarioable->name,
            isset($linea->user) ? $linea->user->name : null,
            $linea->product->name ??  null,
            $linea->subProduct->name  ?? null,
            $linea->productoable->trafico_real ?? null,
            $linea->status,
            isset($linea->productoable->created_at) ? Date::stringToExcel($linea->productoable->created_at) : null,
            isset($linea->productoable->preactivated_at) ? Date::stringToExcel($linea->productoable->preactivated_at) : null,
            isset($linea->productoable->activated_at) ? Date::stringToExcel($linea->productoable->activated_at) : null,
            $linea->productoable->transaction->monto ?? null,
            $linea->productoable->transaction->taecel_message ?? null,
            isset($linea->productoable->transaction->created_at) ?  Date::stringToExcel($linea->productoable->transaction->created_at) : null,




        ];
        if (Auth::user()->hasRole(['administrador', 'super-admin'])) {
            array_push(
                $response,
                $linea->comisiones->porta ?? 0,
                $linea->comisiones->n ?? 0,
                $linea->comisiones->n1 ?? 0,
                $linea->comisiones->n2 ?? 0,
                $linea->comisiones->n3 ?? 0,
                $linea->comisiones->n4 ?? 0,
                $linea->comisiones->n5 ?? 0,
                $linea->comisiones->n6 ?? 0,
                $linea->comisiones->n7 ?? 0,
                $linea->comisiones->n8 ?? 0,
                $linea->comisiones->n9 ?? 0,
                $linea->comisiones->n10 ?? 0,
                $linea->comisiones->n11 ?? 0,
            );
        }

        return $response;
    }
    public function headings(): array
    {
        $response = [
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
            'Fecha porta subida',
            'Fecha Preactivacion',
            'Fecha de Activacion',
            'Monto Recarga',
            'Mensaje Recarga',
            'Fecha Recarga',


        ];

        if (Auth::user()->hasRole(['administrador', 'super-admin'])) {
            array_push(
                $response,

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
            );
        }
        return $response;
    }
    public function columnFormats(): array
    {
        return [

            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'P' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }
    public function styles(Worksheet $sheet)
    {

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true, 'size' => 14, 'color' => array('rgb' => '000000')]],


        ];
    }


    public function collection()
    {
        return $this->lineas;
    }
}
