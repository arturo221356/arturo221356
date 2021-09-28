<?php

namespace App\Exports;

use App\Icc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


class IccsClieanExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $iccs;

    public function __construct()
    {

        $this->iccs = Icc::where('company_id',2)->currentStatus(['Disponible'])->whereHas('inventario', function ($query) {
            $query->where('distribution_id', 1);
        })->with('linea')->get();;
    }

    public function map($icc): array
    {
        return [


            $icc->icc . "F",
            $icc->inventario->inventarioable->name,
            isset($icc->linea->dn) ? $icc->linea->dn: null,
            isset($icc->linea->dn) ? $icc->linea->status: null,


           
        ];
    }

    public function headings(): array
    {
        return [
            'Icc',
            'Inventario',
            'Numero',
            'Estatus Linea'

        ];
    }

    public function collection()
    {

            return $this->iccs;
        
    }
}
