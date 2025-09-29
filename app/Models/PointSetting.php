<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointSetting extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = [
        'key',
        'description',
        'points',
        'is_active',
    ];


    public function scopeActive($query)
    {
        $query->where('is_active', 1);
    }
}
