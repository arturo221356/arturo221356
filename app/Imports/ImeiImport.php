<?php

namespace App\Imports;

use App\Imei;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

class ImeiImport implements ToCollection, WithValidation
{
    private $data; 

    public function __construct(array $data = [])
    {
        $this->data = $data; 
    }

    public function collection(Collection $rows)
    {
        $rows->each(function($row, $key) {
            Imei::create(array_merge([
                'imei'   => $row[0],
            ], $this->data));
        });
    }
    public function rules(): array
    {
        return [
            '0' => 'required|unique:imeis,imei|digits:15',
        ];
    }
}