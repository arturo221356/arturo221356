<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class IccCalculator implements FromArray
{
    private $iccs;

    public function __construct(array $iccs)
    {
        $this->iccs = $iccs;

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->iccs;
    }
}
