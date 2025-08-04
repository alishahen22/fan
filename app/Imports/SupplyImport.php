<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Supply;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplyImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Supply([
            'name_ar'      => $row['name_ar'],
            'name_en'      => $row['name_en'],
            'price'        => $row['price'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name_ar'      => ['required'],
            '*.name_en'      => ['required'],
            '*.price'        => ['required', 'numeric'],
        ];
    }
}
