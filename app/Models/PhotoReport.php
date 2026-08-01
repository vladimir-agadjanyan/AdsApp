<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property Carbon|null $checked_at
 */
class PhotoReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertising_object_id',
        'photo_report_status_id',
        'created_by',
        'comment',
        'checked_by',
        'checked_at',
        'review_comment',
    ];

    protected function casts(): array
    {
        return [
            'checked_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<AdvertisingObject, $this>
     */
    public function advertisingObject(): BelongsTo
    {
        return $this->belongsTo(AdvertisingObject::class);
    }

    /**
     * @return BelongsTo<PhotoReportStatus, $this>
     */
    public function photoReportStatus(): BelongsTo
    {
        return $this->belongsTo(PhotoReportStatus::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    /**
     * @return HasMany<Photo, $this>
     */
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
