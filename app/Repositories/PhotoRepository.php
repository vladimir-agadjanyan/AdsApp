<?php

namespace App\Repositories;

use App\DTO\Photos\CreatePhotoData;
use App\Models\AdvertisingObject;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Collection;

class PhotoRepository
{
    public function __construct(private readonly Photo $photo, private readonly AdvertisingObject $advertisingObject) {}

    public function create(CreatePhotoData $data): Photo
    {
        return $this->photo->create([
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
        $this->photo
            ->newQuery()
            ->where('photo_report_id', $photoReportId)
            ->delete();
    }

    /**
     * @return Collection<int, AdvertisingObject>
     */
    public function getWithoutTodayPhotoReport(): Collection
    {
        return $this->advertisingObject
            ->newQuery()
            ->whereHas(
                'objectStatus',
                fn ($query) => $query->where(
                    'name',
                    'Активный'
                )
            )
            ->whereDoesntHave(
                'photoReports',
                fn ($query) => $query->whereDate(
                    'created_at',
                    today()
                )
            )
            ->get();
    }
}
