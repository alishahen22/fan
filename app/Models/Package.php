<?php
namespace App\Models;

use App\Traits\ActivityLoggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, ActivityLoggable;

    protected $fillable = ['name', 'from', 'to', 'fee'];

    public static function getPackageByAmount($amount)
    {
        return self::where('from', '<=', $amount)
            ->where('to', '>', $amount)
            ->first();
    }
}
