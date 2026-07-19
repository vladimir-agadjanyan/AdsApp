<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AdvertisingObject;

class AdvertisingType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function advertisingObjects(): HasMany
    {
        return $this->hasMany(AdvertisingObject::class);
    }
}
