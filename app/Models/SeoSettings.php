<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSettings extends Model
{
    use HasFactory, ActivityLoggable;

    protected $guarded = [];


}
