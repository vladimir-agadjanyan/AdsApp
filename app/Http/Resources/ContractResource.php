<?php

namespace App\Http\Resources;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * @mixin Contract
     */
    public function toArray(Request $request): array
    {
        /** @var Contract $contract */
        $contract = $this->resource;

        return [
            'id' => $contract->id,
            'number' => $contract->number,
            'contract_date' => $contract->contract_date->toDateString(),
            'start_date' => $contract->start_date->toDateString(),
            'end_date' => $contract->end_date->toDateString(),
            'note' => $contract->note,

            'counterparty' => CounterpartyResource::make(
                $this->whenLoaded('counterparty')
            ),

            'created_by' => UserResource::make(
                $this->whenLoaded('createdBy')
            ),

            'created_at' => $contract->created_at?->toDateTimeString(),
            'updated_at' => $contract->updated_at?->toDateTimeString(),
        ];
    }
}
