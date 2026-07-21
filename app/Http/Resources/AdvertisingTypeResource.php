<?php

namespace App\Http\Resources;

use App\Models\AdvertisingType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisingTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var AdvertisingType $advertisingType */
        $advertisingType = $this->resource;

        return [
            'id' => $advertisingType->id,
            'name' => $advertisingType->name,
        ];
    }
}
