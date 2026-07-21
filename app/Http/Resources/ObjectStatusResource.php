<?php

namespace App\Http\Resources;

use App\Models\ObjectStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ObjectStatus $objectStatus */
        $objectStatus = $this->resource;

        return [
            'id' => $objectStatus->id,
            'name' => $objectStatus->name,
        ];
    }
}