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
use App\Models\User;

class AdvertisingObject extends Model
{
    protected $fillable = [
        'contract_id',
        'advertising_type_id',
        'region_id',
        'city_id',
        'object_status_id',
        'created_by',
        'name',
        'address',
        'latitude',
        'longitude',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

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

    public function photoReports(): HasMany
    {
        return $this->hasMany(PhotoReport::class);
    }

    public function objectStatus(): BelongsTo
    {
        return $this->belongsTo(ObjectStatus::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
