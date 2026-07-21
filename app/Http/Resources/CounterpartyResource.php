<?php

namespace App\Http\Resources;

use App\Models\Counterparty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Counterparty $counterparty */
        $counterparty = $this->resource;

        return [
            'id' => $counterparty->id,
            'name' => $counterparty->name,
        ];
    }
}