<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjectStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
        'is_active',
    ];

    public function advertisingObjects(): HasMany
    {
        return $this->hasMany(AdvertisingObject::class, 'object_status_id');
    }
}
