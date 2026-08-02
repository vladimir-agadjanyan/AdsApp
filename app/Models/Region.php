<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
     * @return HasMany<City, $this>
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * @return HasManyThrough<AdvertisingObject, City, $this>
     */
    public function advertisingObjects(): HasManyThrough
    {
        return $this->hasManyThrough(AdvertisingObject::class, City::class);
    }
}
