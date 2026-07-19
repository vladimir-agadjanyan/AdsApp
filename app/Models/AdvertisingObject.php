<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AdvertisingType;
use App\Models\Contract;
use App\Models\City;
use App\Models\ObjectStatus;
use App\Models\PhotoReport;
use App\Models\Region;

class AdvertisingObject extends Model
{
    protected $fillable = [
        'contract_id',
        'advertising_type_id',
        'region_id',
        'city_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'width',
        'height',
        'status_id',
        'notes',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function advertisingType(): BelongsTo
    {
        return $this->belongsTo(AdvertisingType::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(ObjectStatus::class);
    }

    public function photoReports(): HasMany
    {
        return $this->hasMany(PhotoReport::class);
    }
}
