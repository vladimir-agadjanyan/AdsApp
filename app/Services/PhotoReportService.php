<?php

namespace App\Services;

use App\DTO\AuditLog\CreateAuditLogData;
use App\DTO\Notifications\CreateNotificationData;
use App\DTO\PhotoReports\ApprovePhotoReportData;
use App\DTO\PhotoReports\CreatePhotoReportData;
use App\DTO\PhotoReports\RejectPhotoReportData;
use App\DTO\PhotoReports\UpdatePhotoReportData;
use App\DTO\Photos\CreatePhotoData;
use App\Models\Photo;
use App\Models\PhotoReport;
use App\Repositories\NotificationRepository;
use App\Repositories\PhotoReportRepository;
use App\Repositories\PhotoRepository;
use App\Repositories\UserRepository;
use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class PhotoReportService
{
    public function __construct(
        private readonly PhotoReportRepository $photoReportRepository,
        private readonly PhotoRepository $photoRepository,
        private readonly UserRepository $userRepository,
        private readonly NotificationRepository $notificationRepository,
        private readonly AuditLogService $auditLogService,
    ) {
    }

    public function create(CreatePhotoReportData $data, array $photos): PhotoReport
    {
        $statusId = 1;

        $photoReport = DB::transaction(function () use (
            $data,
            $photos,
            $statusId,
        ) {
            $photoReport = $this->photoReportRepository->create(
                $data,
                $statusId,
            );

            foreach ($photos as $index => $photo) {
                /** @var UploadedFile $photo */
                $path = $photo->store(
                    'photo-reports',
                    'public',
                );

                $photoData = new CreatePhotoData(
                    photoReportId: $photoReport->id,
                    originalName: $photo->getClientOriginalName(),
                    filePath: $path,
                    mimeType: $photo->getMimeType(),
                    fileSize: $photo->getSize(),
                    sortOrder: $index + 1,
                );

                $this->photoRepository->create($photoData);
            }

            return $photoReport;
        });

        $photoReport->load('advertisingObject');

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'created',
                entityType: PhotoReport::class,
                entityId: $photoReport->id,
                description: sprintf(
                    'Создан фотоотчет по объекту «%s»',
                    $photoReport->advertisingObject->name,
                ),
                oldValues: null,
                newValues: [
                    'advertising_object_id' => $photoReport->advertising_object_id,
                    'photo_report_status_id' => $photoReport->photo_report_status_id,
                    'created_by' => $photoReport->created_by,
                    'comment' => $photoReport->comment,
                ],
            ),
        );

        $this->notifyRole(
            'Проверяющий',
            $photoReport,
            'Создан новый фотоотчет',
            sprintf(
                'Создан новый фотоотчет по объекту «%s».',
                $photoReport->advertisingObject->name,
            ),
        );

        return $photoReport;
    }

    public function canEdit(PhotoReport $photoReport): bool
    {
        return $photoReport->photoReportStatus->name !== 'Одобрен';
    }

    public function approve(PhotoReport $photoReport, ApprovePhotoReportData $data): PhotoReport
    {
        if ($photoReport->photoReportStatus->name !== 'На проверке') {
            throw new DomainException(
                'Проверить можно только фотоотчет со статусом «На проверке».',
            );
        }

        $oldValues = [
            'photo_report_status_id' => $photoReport->photo_report_status_id,
            'checked_by' => $photoReport->checked_by,
            'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
            'review_comment' => $photoReport->review_comment,
        ];

        $statusId = 2;
        $photoReport = $this->photoReportRepository->approve($photoReport, $data, $statusId);
        $photoReport->refresh();
        $photoReport->load('advertisingObject');

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'approved',
                entityType: PhotoReport::class,
                entityId: $photoReport->id,
                description: sprintf(
                    'Одобрен фотоотчет по объекту «%s»',
                    $photoReport->advertisingObject->name,
                ),
                oldValues: $oldValues,
                newValues: [
                    'photo_report_status_id' => $photoReport->photo_report_status_id,
                    'checked_by' => $photoReport->checked_by,
                    'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
                    'review_comment' => $photoReport->review_comment,
                ],
            ),
        );

        $this->notifyRole(
            'Менеджер',
            $photoReport,
            'Фотоотчет одобрен',
            sprintf(
                'Фотоотчет по объекту «%s» одобрен.',
                $photoReport->advertisingObject->name,
            ),
        );

        return $photoReport;
    }

    public function reject(PhotoReport $photoReport, RejectPhotoReportData $data): PhotoReport
    {
        if ($photoReport->photoReportStatus->name !== 'На проверке') {
            throw new DomainException(
                'Проверить можно только фотоотчет со статусом «На проверке».',
            );
        }

        $oldValues = [
            'photo_report_status_id' => $photoReport->photo_report_status_id,
            'checked_by' => $photoReport->checked_by,
            'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
            'review_comment' => $photoReport->review_comment,
        ];

        $statusId = 3;
        $photoReport = $this->photoReportRepository->reject($photoReport, $data, $statusId);
        $photoReport->refresh();
        $photoReport->load('advertisingObject');

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'rejected',
                entityType: PhotoReport::class,
                entityId: $photoReport->id,
                description: sprintf(
                    'Отклонен фотоотчет по объекту «%s». Причина: %s',
                    $photoReport->advertisingObject->name,
                    $data->reviewComment,
                ),
                oldValues: $oldValues,
                newValues: [
                    'photo_report_status_id' => $photoReport->photo_report_status_id,
                    'checked_by' => $photoReport->checked_by,
                    'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
                    'review_comment' => $photoReport->review_comment,
                ],
            ),
        );

        $this->notifyRole(
            'Менеджер',
            $photoReport,
            'Фотоотчет отклонен',
            sprintf(
                'Фотоотчет по объекту «%s» отклонен. Причина: %s',
                $photoReport->advertisingObject->name,
                $data->reviewComment,
            ),
        );

        return $photoReport;
    }

    public function update(PhotoReport $photoReport, UpdatePhotoReportData $data, array $photos = []): PhotoReport
    {
        if (! $this->canEdit($photoReport)) {
            throw new DomainException(
                'Одобренный фотоотчет нельзя редактировать.',
            );
        }

        $wasRejected = $photoReport->photoReportStatus->name === 'Отклонен';

        $oldValues = [
            'advertising_object_id' => $photoReport->advertising_object_id,
            'photo_report_status_id' => $photoReport->photo_report_status_id,
            'comment' => $photoReport->comment,
            'checked_by' => $photoReport->checked_by,
            'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
            'review_comment' => $photoReport->review_comment,
        ];

        $photoReport = DB::transaction(function () use (
            $photoReport,
            $data,
            $photos,
            $wasRejected,
        ) {
            if ($wasRejected && empty($photos)) {
                throw new DomainException(
                    'Для повторной отправки отклоненного фотоотчета необходимо добавить новую фотографию.',
                );
            }

            $photoReport = $this->photoReportRepository->update(
                $photoReport,
                $data,
            );

            $sortOrder = (int) $photoReport
                ->photos()
                ->max('sort_order');

            foreach ($photos as $photo) {
                /** @var UploadedFile $photo */
                $path = $photo->store(
                    'photo-reports',
                    'public',
                );

                $photoData = new CreatePhotoData(
                    photoReportId: $photoReport->id,
                    originalName: $photo->getClientOriginalName(),
                    filePath: $path,
                    mimeType: $photo->getMimeType(),
                    fileSize: $photo->getSize(),
                    sortOrder: ++$sortOrder,
                );

                $this->photoRepository->create($photoData);
            }

            if ($wasRejected) {
                $photoReport = $this->photoReportRepository->resubmitForReview(
                    $photoReport,
                    1,
                );
            }

            return $photoReport->refresh();
        });

        $photoReport->load('advertisingObject');

        if ($wasRejected) {
            $this->auditLogService->create(
                new CreateAuditLogData(
                    userId: Auth::id(),
                    action: 'resubmitted',
                    entityType: PhotoReport::class,
                    entityId: $photoReport->id,
                    description: sprintf(
                        'Фотоотчет по объекту «%s» повторно отправлен на проверку',
                        $photoReport->advertisingObject->name,
                    ),
                    oldValues: $oldValues,
                    newValues: [
                        'advertising_object_id' => $photoReport->advertising_object_id,
                        'photo_report_status_id' => $photoReport->photo_report_status_id,
                        'comment' => $photoReport->comment,
                        'checked_by' => $photoReport->checked_by,
                        'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
                        'review_comment' => $photoReport->review_comment,
                    ],
                ),
            );

            $this->notifyRole(
                'Проверяющий',
                $photoReport,
                'Фотоотчет повторно отправлен на проверку',
                sprintf(
                    'Исправленный фотоотчет по объекту «%s» повторно отправлен на проверку.',
                    $photoReport->advertisingObject->name,
                ),
            );
        }

        return $photoReport;
    }

    public function deletePhoto(PhotoReport $photoReport, Photo $photo): void
    {
        if (! $this->canEdit($photoReport)) {
            throw new DomainException(
                'Нельзя изменять фотографии одобренного фотоотчета.',
            );
        }

        if ($photo->photo_report_id !== $photoReport->id) {
            throw new DomainException(
                'Фотография не принадлежит этому фотоотчету.',
            );
        }

        if ($photoReport->photos()->count() <= 1) {
            throw new DomainException(
                'В фотоотчете должна остаться хотя бы одна фотография.',
            );
        }

        Storage::disk('public')->delete(
            $photo->file_path,
        );

        $this->photoRepository->delete($photo);
    }

    public function find(int $id): PhotoReport
    {
        return $this->photoReportRepository->find($id);
    }

    public function canDelete(PhotoReport $photoReport): bool
    {
        return true;
    }

    public function delete(PhotoReport $photoReport): void
    {
        if (! $this->canDelete($photoReport)) {
            throw new RuntimeException(
                'Нельзя удалить фотоотчет.',
            );
        }

        $oldValues = [
            'advertising_object_id' => $photoReport->advertising_object_id,
            'photo_report_status_id' => $photoReport->photo_report_status_id,
            'created_by' => $photoReport->created_by,
            'comment' => $photoReport->comment,
            'checked_by' => $photoReport->checked_by,
            'checked_at' => $photoReport->checked_at?->format('Y-m-d H:i:s'),
            'review_comment' => $photoReport->review_comment,
        ];

        $photoReportId = $photoReport->id;

        $photoReport->load('advertisingObject');

        $objectName = $photoReport->advertisingObject->name;

        DB::transaction(function () use ($photoReport) {
            foreach ($photoReport->photos as $photo) {
                Storage::disk('public')->delete(
                    $photo->file_path,
                );
            }

            $this->photoRepository->deleteByPhotoReportId(
                $photoReport->id,
            );

            $this->photoReportRepository->delete(
                $photoReport,
            );
        });

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'deleted',
                entityType: PhotoReport::class,
                entityId: $photoReportId,
                description: sprintf(
                    'Удален фотоотчет по объекту «%s»',
                    $objectName,
                ),
                oldValues: $oldValues,
                newValues: null,
            ),
        );
    }

    private function notifyRole(string $roleName, PhotoReport $photoReport, string $title, string $message): void
    {
        $users = $this->userRepository->findByRoleName($roleName);

        foreach ($users as $user) {
            $this->notificationRepository->create(
                new CreateNotificationData(
                    userId: $user->id,
                    advertisingObjectId: $photoReport->advertising_object_id,
                    photoReportId: $photoReport->id,
                    title: $title,
                    message: $message,
                ),
            );
        }
    }
}