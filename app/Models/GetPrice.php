<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GetPrice extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'user_id',
            'subject',
            'message',
            'reply',
            'seen_at',
            'file'
        ];


    public function files(): HasMany
    {
        return $this->hasMany(GetPriceFile::class, 'get_price_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function getFileAttribute($image)
    {

        if (!empty($image) && file_exists(public_path('storage/get_price_files/' . $image))) {
            return asset('storage') . '/get_price_files/' . $image;
        }
        return null;
    }
}
