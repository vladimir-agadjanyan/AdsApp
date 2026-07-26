<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Counterparty extends Model
{
    use HasFactory;

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
