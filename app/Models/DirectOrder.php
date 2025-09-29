<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectOrder extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable =
        [
            'name',
            'email',
            'phone',
            'subject',
            'message',
            'reply',
            'seen_at'
        ];


    public function files()
    {
        return $this->hasMany(DirectOrderFile::class, 'direct_order_id', 'id');
    }
}
