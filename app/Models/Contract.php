<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AdvertisingObject;
use App\Models\Counterparty;
use App\Models\ContractFile;
use App\Models\User;

class Contract extends Model
{
    protected $fillable = [
        'counterparty_id',
        'number',
        'date',
        'start_date',
        'end_date',
        'total_amount',
        'comment',
        'created_by',

    ];

    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function contractFiles(): HasMany
    {
        return $this->hasMany(ContractFile::class);
    }

    public function advertisingObjects(): HasMany
    {
        return $this->hasMany(AdvertisingObject::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
