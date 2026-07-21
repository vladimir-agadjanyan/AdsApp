<?php

namespace App\Http\Resources;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class PhotoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Photo $photo */
        $photo = $this->resource;

        return [
            'id' => $photo->id,
            'original_name' => $photo->original_name,

            'file_path' => $photo->file_path,
            'url' => Storage::disk('public')->url($photo->file_path),

            'mime_type' => $photo->mime_type,
            'file_size' => $photo->file_size,
            'sort_order' => $photo->sort_order,

            'created_at' => $photo->created_at?->toDateTimeString(),
            'updated_at' => $photo->updated_at?->toDateTimeString(),
        ];
    }
}