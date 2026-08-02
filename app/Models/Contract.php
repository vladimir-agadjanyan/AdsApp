<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $counterparty_id
 * @property string $number
 * @property string $note
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $contract_date
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'counterparty_id',
        'number',
        'contract_date',
        'start_date',
        'end_date',
        'amount',
        'note',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'contract_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }
    /**
     * @return BelongsTo<Counterparty, $this>
     */
    public function counterparty(): BelongsTo
    {
        return $this->belongsTo(Counterparty::class);
    }

    public function advertisingObjects(): HasMany
    {
        return $this->hasMany(AdvertisingObject::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ContractFile::class)
            ->whereNull('contract_addendum_id');
    }

    public function addendums(): HasMany
    {
        return $this->hasMany(ContractAddendum::class);
    }

    public function getStatusAttribute(): string
    {
        $today = Carbon::today();

        if ($this->end_date < $today) {
            return 'expired';
        }

        if ($this->end_date <= $today->copy()->addDays(30)) {
            return 'expiring';
        }

        return 'active';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Активный',
            'expiring' => 'Истекает',
            'expired' => 'Истёк',
            default => 'Неизвестно',

        };
    }

    public function getStatusClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'expiring' => 'warning',
            'expired' => 'danger',
            default => 'secondary',
        };
    }

    public function getAddendumsAmountAttribute(): float
    {
        return (float) (
            $this->relationLoaded('addendums')
                ? $this->addendums->sum('amount_change')
                : $this->addendums()->sum('amount_change')
        );
    }

    public function getTotalAmountAttribute(): float
    {
        return (float) $this->amount + $this->addendums_amount;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>', today()->copy()->addDays(30));
    }

    public function scopeExpiring(Builder $query): Builder
    {
        return $query
            ->whereDate('end_date', '>=', today())
            ->whereDate('end_date', '<=', today()->copy()->addDays(30));
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query
            ->whereDate('end_date', '<', today());
    }
}
