<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeOption extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = ['attribute_id',
                        'title_ar',
                        'title_en',
                        'is_active',];

    protected $appends = ['title'];

    public function getTitleAttribute()
    {
        if (\app()->getLocale() == "en") {
            return $this->title_en;
        } else {
            return $this->title_ar;
        }
    }
}
