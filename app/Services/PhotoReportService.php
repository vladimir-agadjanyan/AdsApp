<?php

namespace App\Services;

use App\Models\PhotoReport;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use DomainException;

class PhotoReportService
{
    public function create(array $data, array $photos): PhotoReport
    {
        return DB::transaction(function () use ($data, $photos) {

            $photoReport = PhotoReport::create([
                'advertising_object_id'   => $data['advertising_object_id'],
                'photo_report_status_id'  => 1, // На проверке
                'created_by'              => auth()->id(),
                'comment'                 => $data['comment'] ?? null,
            ]);

            foreach ($photos as $index => $photo) {

                /** @var UploadedFile $photo */

                $path = $photo->store('photo-reports', 'public');

                Photo::create([
                    'photo_report_id' => $photoReport->id,
                    'original_name'   => $photo->getClientOriginalName(),
                    'file_path'       => $path,
                    'mime_type'       => $photo->getMimeType(),
                    'file_size'       => $photo->getSize(),
                    'sort_order'      => $index + 1,
                ]);
            }

            return $photoReport;
        });
    }

    public function canEdit(PhotoReport $photoReport): bool
    {
        return $photoReport->photoReportStatus->name !== 'Одобрен';
    }

    public function update(
        PhotoReport $photoReport,
        array $data,
        array $photos = []
    ): PhotoReport {
        if (! $this->canEdit($photoReport)) {
            throw new DomainException(
                'Одобренный фотоотчет нельзя редактировать.'
            );
        }

        return DB::transaction(function () use ($photoReport, $data, $photos) {

            $photoReport->update($data);

            $sortOrder = (int) $photoReport->photos()
                ->max('sort_order');

            foreach ($photos as $photo) {

                /** @var UploadedFile $photo */

                $path = $photo->store(
                    'photo-reports',
                    'public'
                );

                Photo::create([
                    'photo_report_id' => $photoReport->id,
                    'original_name' => $photo->getClientOriginalName(),
                    'file_path' => $path,
                    'mime_type' => $photo->getMimeType(),
                    'file_size' => $photo->getSize(),
                    'sort_order' => ++$sortOrder,
                ]);
            }

            return $photoReport->refresh();
        });
    }

    public function deletePhoto(
        PhotoReport $photoReport,
        Photo $photo
    ): void {
        if (! $this->canEdit($photoReport)) {
            throw new DomainException(
                'Нельзя изменять фотографии одобренного фотоотчета.'
            );
        }

        if ($photo->photo_report_id !== $photoReport->id) {
            throw new DomainException(
                'Фотография не принадлежит этому фотоотчету.'
            );
        }

        if ($photoReport->photos()->count() <= 1) {
            throw new DomainException(
                'В фотоотчете должна остаться хотя бы одна фотография.'
            );
        }

        Storage::disk('public')->delete(
            $photo->file_path
        );

        $photo->delete();
    }

    public function find(int $id): PhotoReport
    {
        return PhotoReport::findOrFail($id);
    }

    public function canDelete(PhotoReport $photoReport): bool
    {
        return true;
    }

    public function delete(PhotoReport $photoReport): void
    {
        if (! $this->canDelete($photoReport)) {
            throw new RuntimeException('Нельзя удалить фотоотчет.');
        }

        DB::transaction(function () use ($photoReport) {

            foreach ($photoReport->photos as $photo) {
                Storage::disk('public')->delete($photo->file_path);
            }

            $photoReport->photos()->delete();

            $photoReport->delete();

        });

    }
}