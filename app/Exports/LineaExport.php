<?php

namespace App\Exports;

use App\Linea;
use Maatwebsite\Excel\Concerns\FromCollection;

use Maatwebsite\Excel\Concerns\WithMapping;

use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LineaExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
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
            $linea->icc->inventario->inventarioable->name,
            isset($linea->product->name) ? $linea->product->name : null,
            $linea->status,
            $linea->productoable->preactivated_at,
            $linea->productoable->activated_at,
            isset($linea->productoable->transaction->monto) ? $linea->productoable->transaction->monto : null,
            isset($linea->productoable->transaction->taecel_message) ? $linea->productoable->transaction->taecel_message : null,
            isset($linea->productoable->transaction->created_at) ? $linea->productoable->transaction->created_at : null,



        ];
    }
    public function headings(): array
    {
        return [
            'Icc',
            'Numero',
            'Inventario',
            'Producto',
            'Estatus',
            'Fecha Preactivacion',
            'Fecha de Activacion',
            'Monto Recarga',
            'Mensaje Recarga',
            'Fecha Recarga'
        ];
    }


    public function collection()
    {
        return $this->lineas;
    }
}
