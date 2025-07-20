<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar', 'name_en', 'price', 'image', 'is_active',
    ];

    public function printServices()
    {
        return $this->belongsToMany(PrintService::class);
    }

    //   public function quotationItems()
    //     {
    //         return $this->belongsToMany(QuotationItem::class, 'quotation_item_supply') ;
    //     }
}
