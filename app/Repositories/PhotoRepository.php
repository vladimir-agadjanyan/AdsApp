<?php

namespace App\Repositories;

use App\DTO\Photos\CreatePhotoData;
use App\Models\Photo;

class PhotoRepository
{
    public function create(CreatePhotoData $data): Photo
    {
        return Photo::create([
            'photo_report_id' => $data->photoReportId,
            'original_name' => $data->originalName,
            'file_path' => $data->filePath,
            'mime_type' => $data->mimeType,
            'file_size' => $data->fileSize,
            'sort_order' => $data->sortOrder,
        ]);
    }

    public function delete(Photo $photo): void
    {
        $photo->delete();
    }

    public function deleteByPhotoReportId(int $photoReportId): void
    {
        Photo::query()
            ->where('photo_report_id', $photoReportId)
            ->delete();
    }
}