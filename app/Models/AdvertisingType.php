<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
