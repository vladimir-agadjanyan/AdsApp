<?php

namespace App\Services;

use App\Models\PhotoReport;
use RuntimeException;

class PhotoReportService
{
    public function create(array $data): PhotoReport
    {
        return PhotoReport::create($data);
    }

    public function update(PhotoReport $photoReport, array $data): PhotoReport
    {
        $photoReport->update($data);

        return $photoReport->refresh();
    }

    public function find(int $id): PhotoReport
    {
        return PhotoReport::findOrFail($id);
    }

    public function delete(PhotoReport $photoReport): void
    {
        if (! $this->canDelete($photoReport)) {
            throw new RuntimeException('Нельзя удалить фотоотчет.');
        }

        $photoReport->delete();
    }

    public function canDelete(PhotoReport $photoReport): bool
    {
        return true;
    }
}