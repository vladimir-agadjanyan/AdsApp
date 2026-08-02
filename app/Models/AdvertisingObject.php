<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvertisingObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contract_id',
        'advertising_type_id',
        'city_id',
        'address',
        'latitude',
        'longitude',
        'object_status_id',
        'created_by',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * @return BelongsTo<Contract, $this>
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo<AdvertisingType, $this>
     */
    public function advertisingType(): BelongsTo
    {
        return $this->belongsTo(AdvertisingType::class);
    }

    /**
     * @return HasMany<PhotoReport, $this>
     */
    public function photoReports(): HasMany
    {
        return $this->hasMany(PhotoReport::class);
    }

    /**
     * @return BelongsTo<ObjectStatus, $this>
     */
    public function objectStatus(): BelongsTo
    {
        return $this->belongsTo(ObjectStatus::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
