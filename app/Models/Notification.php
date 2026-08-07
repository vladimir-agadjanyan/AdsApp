<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'advertising_object_id',
        'photo_report_id',
        'contract_id',
        'title',
        'message',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<AdvertisingObject, $this>
     */
    public function advertisingObject(): BelongsTo
    {
        return $this->belongsTo(AdvertisingObject::class);
    }

    /**
     * @return BelongsTo<Contract, $this>
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * @return BelongsTo<PhotoReport, $this>
     */
    public function photoReport(): BelongsTo
    {
        return $this->belongsTo(PhotoReport::class);
    }
}
