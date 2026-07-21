<?php

namespace App\Http\Resources;

use App\Models\PhotoReport;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PhotoReport $photoReport */
        $photoReport = $this->resource;

        return [
            'id' => $photoReport->id,
            'comment' => $photoReport->comment,
            'checked_at' => $photoReport->checked_at?->toDateTimeString(),
            'review_comment' => $photoReport->review_comment,

            'advertising_object' => AdvertisingObjectResource::make(
                $this->whenLoaded('advertisingObject')
            ),

            'photo_report_status' => PhotoReportStatusResource::make(
                $this->whenLoaded('photoReportStatus')
            ),

            'created_by' => UserResource::make(
                $this->whenLoaded('createdBy')
            ),

            'checked_by' => UserResource::make(
                $this->whenLoaded('checkedBy')
            ),

            'created_at' => $photoReport->created_at?->toDateTimeString(),
            'updated_at' => $photoReport->updated_at?->toDateTimeString(),
        ];
    }
}