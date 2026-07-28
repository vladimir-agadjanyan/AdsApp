<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\ContractFile;
use App\Services\ContractFileService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use WithFileUploads;

    public Contract $contract;

    #[Validate([
        'documents.*' => [
            'nullable',
            'file',
            'max:10240',
            'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ],
    ])]
    public $document;

    public function save(ContractFileService $contractFileService): void
    {
        $this->validate();

        try {
            $contractFileService->upload(
                contract: $this->contract,
                file: $this->document,
                uploadedBy: auth()->id(),
            );

            $this->reset('document');

            session()->flash('success', 'Файл успешно загружен.');
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function download(ContractFile $file)
    {
        return app(ContractFileService::class)->download($file);
    }

    public function delete(ContractFile $file): void
    {
        app(ContractFileService::class)->delete($file);

        session()->flash('success', 'Файл удалён.');
    }

    public function render()
    {
        return view('livewire.contracts.documents');
    }
}
