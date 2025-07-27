<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintSetting extends Model
{
    use HasFactory;
      protected $fillable = ['key', 'value'];


         public function getLogoAttribute()
            {
                if (!empty($this->value)) {
                    if (file_exists(public_path('storage/' . $this->value))) {
                        return asset('storage') . '/' . $this->value;
                    }
                    return asset('storage/default.png');
                }
                return null;
            }

    public function setLogoAttribute()
    {
        if (!empty($this->value)) {
            $imageFields = $this->value;
            if (is_file($this->value)) {
                $imageFields = upload($this->value);
            }
            $this->attributes['value'] = $imageFields;
        }
    }

}
