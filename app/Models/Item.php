<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

     protected $fillable = [
        'name_ar', 'name_en', 'type', 'width_cm', 'height_cm',
        'price', 'weight_grams', 'notes', 'image', 'is_active'
    ];

    public function printServices()
    {
        return $this->belongsToMany(PrintService::class);
    }

}
