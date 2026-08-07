<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $region_id
 * @property string $name
 */
class City extends Model
{
    protected $fillable = [
        'name',
        'region_id',
    ];

    /**
     * @return BelongsTo<Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    /**
     * @return HasMany<AdvertisingObject, $this>
     */
    public function advertisingObjects(): HasMany
    {
        return $this->hasMany(AdvertisingObject::class);
    }
}
