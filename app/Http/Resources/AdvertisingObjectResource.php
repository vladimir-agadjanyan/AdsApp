<?php

namespace App\Http\Resources;

use App\Models\AdvertisingObject;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisingObjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var AdvertisingObject $object */
        $object = $this->resource;

        return [
            'id' => $object->id,
            'name' => $object->name,
            'address' => $object->address,
            'latitude' => $object->latitude,
            'longitude' => $object->longitude,
            'note' => $object->note,

            'contract' => ContractResource::make(
                $this->whenLoaded('contract')
            ),

            'advertising_type' => AdvertisingTypeResource::make(
                $this->whenLoaded('advertisingType')
            ),

            'region' => RegionResource::make(
                $this->whenLoaded('region')
            ),

            'city' => CityResource::make(
                $this->whenLoaded('city')
            ),

            'object_status' => ObjectStatusResource::make(
                $this->whenLoaded('objectStatus')
            ),

            'created_by' => UserResource::make(
                $this->whenLoaded('createdBy')
            ),

            'created_at' => $object->created_at?->toDateTimeString(),
            'updated_at' => $object->updated_at?->toDateTimeString(),
        ];
    }
}