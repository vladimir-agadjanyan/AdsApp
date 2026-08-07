<?php

namespace App\Repositories;

use App\DTO\PhotoReports\CreatePhotoReportData;
use App\DTO\PhotoReports\UpdatePhotoReportData;
use App\DTO\PhotoReports\ApprovePhotoReportData;
use App\DTO\PhotoReports\RejectPhotoReportData;
use App\Models\PhotoReport;

class PhotoReportRepository
{
    public function create(CreatePhotoReportData $data, int $statusId): PhotoReport
    {
        return PhotoReport::create([
            'advertising_object_id' => $data->advertisingObjectId,
            'photo_report_status_id' => $statusId,
            'created_by' => $data->createdBy,
            'comment' => $data->comment,
        ]);
    }

    public function update(PhotoReport $photoReport, UpdatePhotoReportData $data): PhotoReport
    {
        $photoReport->update([
            'advertising_object_id' => $data->advertisingObjectId,
            'comment' => $data->comment,
        ]);

        return $photoReport;
    }

    public function resubmitForReview(PhotoReport $photoReport, int $statusId): PhotoReport
    {
        $photoReport->update([
            'photo_report_status_id' => $statusId,
            'checked_by' => null,
            'checked_at' => null,
            'review_comment' => null,
        ]);

        return $photoReport;
    }

    public function approve(PhotoReport $photoReport, ApprovePhotoReportData $data, int $statusId): PhotoReport
    {
        $photoReport->update([
            'photo_report_status_id' => $statusId,
            'checked_by' => $data->checkedBy,
            'checked_at' => now(),
            'review_comment' => $data->reviewComment,
        ]);

        return $photoReport;
    }

    public function reject(PhotoReport $photoReport, RejectPhotoReportData $data, int $statusId): PhotoReport
    {
        $photoReport->update([
            'photo_report_status_id' => $statusId,
            'checked_by' => $data->checkedBy,
            'checked_at' => now(),
            'review_comment' => $data->reviewComment,
        ]);

        return $photoReport;
    }

    public function find(int $id): PhotoReport
    {
        return PhotoReport::findOrFail($id);
    }

    public function delete(PhotoReport $photoReport): void
    {
        $photoReport->delete();
    }

}