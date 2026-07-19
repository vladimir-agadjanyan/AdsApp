<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'name'
    ];

    public function cities(): HasMany
    {
       return $this->hasMany(City::class);
    }
}
