<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Contract;

class Counterparty extends Model
{
    protected $fillable = [
        'name',
        'inn',
        'phone',
        'email',
        'address',
        'contact_person',
        'note',
    ];

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
