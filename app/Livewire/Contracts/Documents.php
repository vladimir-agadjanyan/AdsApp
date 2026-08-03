<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\ContractFile;
use App\Services\Contract\ContractFileService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Documents extends Component
{
    use WithFileUploads;

    public Contract $contract;

    #[Validate([
        'nullable',
        'file',
        'max:10240',
        'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
    ])]
    public $document;

    public function save(ContractFileService $contractFileService): void
    {
        $this->validate();

        $contractFileService->upload(contract: $this->contract, file: $this->document, uploadedBy: auth()->id(),);

        $this->reset('document');
        $this->contract->load('files.uploadedBy');

        session()->flash('success', 'Файл успешно загружен.');
    }

    public function download(ContractFile $file)
    {
        abort_unless($file->contract_id === $this->contract->id, 404);

        return app(ContractFileService::class)
            ->download($file);
    }

    public function delete(ContractFile $file): void
    {
        abort_unless($file->contract_id === $this->contract->id, 404);

        app(ContractFileService::class)
            ->delete($file);

        $this->contract->load('files.uploader');

        session()->flash('success', 'Файл удалён.');
    }

    public function render()
    {
        return view('livewire.contracts.documents');
    }
}