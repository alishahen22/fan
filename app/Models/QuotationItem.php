<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    use HasFactory;


    protected $guarded = [];
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_quotation_item');
    }

    //get supplies attribute
    public function getSuppliesAttribute()
    {
        if ($this->supplies_ids) {
            return json_decode($this->supplies_ids, true);
        }
        return [];
    }

    // public function supplies()
    // {
    //     return $this->belongsToMany(Supply::class, 'quotation_item_supply', 'item_id');
    // }
}