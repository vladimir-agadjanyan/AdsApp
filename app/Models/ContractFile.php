<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

/**
 * @property-read string $human_file_size
 * @property-read string $extension
 */
class ContractFile extends Model
{
    protected $fillable = [
        'contract_id',
        'contract_addendum_id',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function contractAddendum(): BelongsTo
    {
        return $this->belongsTo(ContractAddendum::class, 'contract_addendum_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getHumanFileSizeAttribute(): string
    {
        return Number::fileSize($this->file_size);
    }

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    public function getBadgeClassAttribute(): string
    {
        return match ($this->extension) {
            'PDF' => 'bg-danger',
            'DOC', 'DOCX' => 'bg-primary',
            'XLS', 'XLSX' => 'bg-success',
            'JPG', 'JPEG', 'PNG' => 'bg-dark',
            default => 'bg-secondary',
        };
    }
}
