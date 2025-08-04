<?php

namespace App\Imports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Item([
            'name_ar'      => $row['name_ar'],
            'name_en'      => $row['name_en'],
            'type'         => $row['type'],
            'width_cm'     => $row['width_cm'],
            'height_cm'    => $row['height_cm'],
            'price'        => $row['price'],
            'weight_grams' => $row['weight_grams'],
            'notes'        => $row['notes'],
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name_ar'      => ['required'],
            '*.name_en'      => ['required'],
            '*.type'         => ['required'],
            '*.width_cm'     => ['required', 'numeric'],
            '*.height_cm'    => ['required', 'numeric'],
            '*.price'        => ['required', 'numeric'],
            '*.weight_grams' => ['nullable', 'numeric'],
            '*.notes'        => ['nullable'],
        ];
    }
}
