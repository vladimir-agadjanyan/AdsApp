<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotoReportStatus extends Model
{
    protected $fillable = [
        'name',
        'color',
        'sort_order',
        'is_active',
    ];

    public function photoReports(): HasMany
    {
        return $this->hasMany(PhotoReport::class, 'photo_report_status_id');
    }
}
