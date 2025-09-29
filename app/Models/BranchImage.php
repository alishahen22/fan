<?php

namespace App\Models;

use App\Traits\ActivityLoggable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchImage extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = [
        'branch_id',
        'image',
    ];

    public function getImageAttribute($image)
    {
        if (!empty($image) && file_exists(public_path('storage/' . $image))) {
            return asset('storage') . '/' . $image;
        }
        return asset('storage/default.png');
    }

    public function setImageAttribute($image)
    {
        if (!empty($image)) {
            $imageFields = $image;
            if (is_file($image)) {
                $imageFields = upload($image);
            }
            $this->attributes['image'] = $imageFields;
        }
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
