<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintService extends Model
{
    use HasFactory;

    //appends
    protected $appends = ['total_price' ,'item_price'];
    //guarded
    protected $guarded = [];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    public function supplies()
    {
        return $this->belongsToMany(Supply::class);
    }


    public function getTotalPriceAttribute()
    {
        return $this->calculateTotalPrice()['total_price'] ?? 0;
    }

    public function getItemPriceAttribute()
    {
        return  $this->calculateTotalPrice()['unit_price'] ?? 0;
    }

    public function calculateTotalPrice()
    {
        $paperTotal = 0;

        foreach ($this->items as $item) {
            $sheetArea = $item->width_cm * $item->height_cm;
            $cardArea = $this->width * $this->height;
            if ($cardArea <= 0 || $sheetArea <= 0) continue;

            $cardsPerSheet = floor($sheetArea / $cardArea);
            if ($cardsPerSheet <= 0) $cardsPerSheet = 1;

            $sheetsRequired = ceil($this->quantity / $cardsPerSheet);
            $paperTotal += $sheetsRequired * $item->price;
        }

        $suppliesTotal = $this->supplies->sum('price');
        $totalPrice = $paperTotal + $suppliesTotal;

        return [
                'total_price' => round($totalPrice, 2),
                'unit_price' => round($totalPrice / $this->quantity, 4),
            ];
    }
}
