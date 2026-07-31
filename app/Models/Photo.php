<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $file_path
 * @property string $original_name
 * @property string $mime_type
 * @property int $file_size
 * @property int $sort_order
 */
class Photo extends Model
{
    protected $fillable = [
        'photo_report_id',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    public function photoReport(): BelongsTo
    {
        return $this->belongsTo(PhotoReport::class);
    }
}
