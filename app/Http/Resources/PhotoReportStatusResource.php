<?php

namespace App\Http\Resources;

use App\Models\PhotoReportStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhotoReportStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PhotoReportStatus $photoReportStatus */
        $photoReportStatus = $this->resource;

        return [
            'id' => $photoReportStatus->id,
            'name' => $photoReportStatus->name,
        ];
    }
}
