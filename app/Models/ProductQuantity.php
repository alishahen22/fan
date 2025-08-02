<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductQuantity extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        ];


    protected $appends = ['price_original'];
     public function getPriceAttribute()
    {


       //check i user not in dashboard
       if (in_array(auth()->user()?->type, ['admin', 'assistant', 'printer'])) {
        return $this->attributes['price'];
       }
       $price = $this->attributes['price'];
       if ($this->product?->discount > 0) {
           $discount_amount = $price * ($this->product?->discount / 100);
           $price = $price - $discount_amount;
       }
       return $price;    }

    //get price before discount
    public function getPriceOriginalAttribute()
    {
        return $this->attributes['price'];
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    }