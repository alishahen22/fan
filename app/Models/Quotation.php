<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory, ActivityLoggable;
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
