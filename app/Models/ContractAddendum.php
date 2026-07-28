<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $signed_at
 * @property Carbon|null $end_date
 * @property float $amount_change
 * @property-read Contract $contract
 * @property-read User $createdBy
 * @property-read string $formatted_amount_change
 */
class ContractAddendum extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'number',
        'signed_at',
        'end_date',
        'amount_change',
        'note',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'date',
            'end_date' => 'date',
            'amount_change' => 'decimal:2',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContractFile::class, 'contract_addendum_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedAmountChangeAttribute(): string
    {
        $amount = number_format($this->amount_change, 0, ',', ' ');

        return $this->amount_change > 0
            ? "+{$amount}"
            : $amount;
    }
}
