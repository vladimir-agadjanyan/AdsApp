<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AdvertisingObject;
use App\Models\PhotoReportStatus;
use App\Models\Photo;
use App\Models\User;

class PhotoReport extends Model
{
    protected $fillable = [
        'advertising_object_id',
        'created_by',
        'status_id',
        'comment',
        'checked_by',
        'checked_at',
        'review_comment',
    ];

    public function advertisingObject(): BelongsTo
    {
        return $this->belongsTo(AdvertisingObject::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(PhotoReportStatus::class);
    }

    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
