<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Photo;
use App\Models\PhotoReport;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DomainException;
use RuntimeException;

class PhotoReportService
{
    public function create(array $data, array $photos): PhotoReport
    {
        $photoReport = DB::transaction(function () use ($data, $photos) {

            $photoReport = PhotoReport::create([
                'advertising_object_id' => $data['advertising_object_id'],
                'photo_report_status_id' => 1,
                'created_by' => auth()->id(),
                'comment' => $data['comment'] ?? null,
            ]);

            foreach ($photos as $index => $photo) {

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
                    'sort_order' => $index + 1,
                ]);
            }

            return $photoReport;
        });

        $photoReport->load('advertisingObject');

        $this->notifyRole(
            'Проверяющий',
            $photoReport,
            'Создан новый фотоотчет',
            sprintf(
                'Создан новый фотоотчет по объекту «%s».',
                $photoReport->advertisingObject->name
            )
        );

        return $photoReport;
    }

    public function canEdit(PhotoReport $photoReport): bool
    {
        return $photoReport->photoReportStatus->name !== 'Одобрен';
    }

    public function approve(PhotoReport $photoReport, int $checkedBy, ?string $reviewComment = null): PhotoReport
    {
        if ($photoReport->photoReportStatus->name !== 'На проверке') {
            throw new DomainException(
                'Проверить можно только фотоотчет со статусом «На проверке».'
            );
        }

        $photoReport->update([
            'photo_report_status_id' => 2,
            'checked_by' => $checkedBy,
            'checked_at' => now(),
            'review_comment' => $reviewComment,
        ]);

        $photoReport->refresh();
        $photoReport->load('advertisingObject');

        $this->notifyRole(
            'Менеджер',
            $photoReport,
            'Фотоотчет одобрен',
            sprintf(
                'Фотоотчет по объекту «%s» одобрен.',
                $photoReport->advertisingObject->name
            )
        );

        return $photoReport;
    }

    public function reject(PhotoReport $photoReport, int $checkedBy, string $reviewComment): PhotoReport
    {
        if ($photoReport->photoReportStatus->name !== 'На проверке') {
            throw new DomainException(
                'Проверить можно только фотоотчет со статусом «На проверке».'
            );
        }

        $photoReport->update([
            'photo_report_status_id' => 3,
            'checked_by' => $checkedBy,
            'checked_at' => now(),
            'review_comment' => $reviewComment,
        ]);

        $photoReport->refresh();
        $photoReport->load('advertisingObject');

        $this->notifyRole(
            'Менеджер',
            $photoReport,
            'Фотоотчет отклонен',
            sprintf(
                'Фотоотчет по объекту «%s» отклонен. Причина: %s',
                $photoReport->advertisingObject->name,
                $reviewComment
            )
        );

        return $photoReport;
    }

    public function update(PhotoReport $photoReport, array $data, array $photos = []): PhotoReport
    {
        if (! $this->canEdit($photoReport)) {
            throw new DomainException(
                'Одобренный фотоотчет нельзя редактировать.'
            );
        }

        $wasRejected =
            $photoReport->photoReportStatus->name === 'Отклонен';

        $photoReport = DB::transaction(
            function () use (
                $photoReport,
                $data,
                $photos,
                $wasRejected
            ) {
                if ($wasRejected && empty($photos)) {
                    throw new DomainException(
                        'Для повторной отправки отклоненного фотоотчета необходимо добавить новую фотографию.'
                    );
                }

                $photoReport->update($data);

                $sortOrder = (int) $photoReport
                    ->photos()
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

                if ($wasRejected) {
                    $photoReport->update([
                        'photo_report_status_id' => 1,
                        'checked_by' => null,
                        'checked_at' => null,
                        'review_comment' => null,
                    ]);
                }

                return $photoReport->refresh();
            }
        );

        if ($wasRejected) {
            $photoReport->load('advertisingObject');

            $this->notifyRole(
                'Проверяющий',
                $photoReport,
                'Фотоотчет повторно отправлен на проверку',
                sprintf(
                    'Исправленный фотоотчет по объекту «%s» повторно отправлен на проверку.',
                    $photoReport->advertisingObject->name
                )
            );
        }

        return $photoReport;
    }

    public function deletePhoto(PhotoReport $photoReport, Photo $photo): void
    {
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
            throw new RuntimeException(
                'Нельзя удалить фотоотчет.'
            );
        }

        DB::transaction(function () use ($photoReport) {

            foreach ($photoReport->photos as $photo) {
                Storage::disk('public')->delete(
                    $photo->file_path
                );
            }

            $photoReport->photos()->delete();

            $photoReport->delete();
        });
    }

    private function notifyRole(string $roleName, PhotoReport $photoReport, string $title, string $message): void
    {
        $users = User::query()
            ->whereHas(
                'role',
                fn ($query) => $query->where(
                    'name',
                    $roleName
                )
            )
            ->get();

        foreach ($users as $user) {
            Notification::query()->create([
                'user_id' => $user->id,
                'advertising_object_id' => $photoReport->advertising_object_id,
                'photo_report_id' => $photoReport->id,
                'title' => $title,
                'message' => $message,
            ]);
        }
    }
}