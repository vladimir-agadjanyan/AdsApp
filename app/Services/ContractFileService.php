<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\ContractFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContractFileService
{
    /**
     * Загружает файл договора и создает запись в БД.
     */
    public function upload(
        Contract $contract,
        TemporaryUploadedFile $file,
        ?int $contractAddendumId = null,
        ?int $uploadedBy = null,
    ): ContractFile {
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize();

        $filePath = $file->store(
            "contracts/{$contract->id}",
            'local'
        );
        try {
            return ContractFile::create([
                'contract_id' => $contract->id,
                'contract_addendum_id' => $contractAddendumId,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'uploaded_by' => $uploadedBy,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('local')->delete($filePath);

            throw $e;
        }
    }

    /**
     * Скачивает файл.
     */
    public function download(ContractFile $file): BinaryFileResponse
    {
        return response()->download(
            Storage::disk('local')->path($file->file_path),
            $file->original_name,
        );
    }

    /**
     * Удаляет файл и запись из БД.
     */
    public function delete(ContractFile $file): void
    {
        Storage::disk('local')->delete($file->file_path);

        $file->delete();
    }
}
