<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = [
        'user_id',
        'order_id',
        'points',
        'type',
        'money',
    ];

    const TYPE = ['add', 'change'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }


}
