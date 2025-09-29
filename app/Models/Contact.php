<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = [
        'first_name',
        'email',
        'subject',
        'message',
        'seen_at',
    ];

    protected $casts = [
        'seen_at' => 'datetime',
    ];
}
