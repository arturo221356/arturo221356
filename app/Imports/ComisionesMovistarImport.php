<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Maatwebsite\Excel\Concerns\WithChunkReading;


class ComisionesMovistarImport implements WithMultipleSheets, ShouldQueue, WithChunkReading
{

    use WithConditionalSheets;
   
    public function conditionalSheets(): array
    {
        return[
            'MESN' => new movistarNimport(),
            'MESN-1' => new movistarN1import(),
            'MESN-2' => new movistarN2import(),
            'MESN-3' => new movistarN3import(),
            'MESN-4' => new movistarN4import(),
            'MESN-5' => new movistarN5import(),
            'MESN-6' => new movistarN6import(),
        ];

       
    }

            public function chunkSize(): int
    {
        return 1000;
    }



}
