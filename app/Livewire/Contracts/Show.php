<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\ContractAddendum;
use App\Models\ContractFile;
use App\Services\ContractFileService;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class Show extends Component
{
    use WithFileUploads;

    public Contract $contract;

    #[Validate([
        'document' => [
            'required',
            'file',
            'max:10240',
            'mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',
        ],
    ])]
    public $document;

    public function mount(Contract $contract): void
    {
        $this->contract = $contract->load([
            'counterparty',
            'addendums',
            'files.uploadedBy',
        ]);
    }

    public function upload(ContractFileService $contractFileService): void
    {
        $this->validate();

        $contractFileService->upload(
            contract: $this->contract,
            file: $this->document,
            uploadedBy: auth()->id(),
        );

        $this->reset('document');

        $this->contract->load([
            'counterparty',
            'addendums',
            'files.uploadedBy',
        ]);

        session()->flash(
            'success',
            'Документ успешно загружен.'
        );
    }

    public function download( ContractFile $file,  ContractFileService $contractFileService ): BinaryFileResponse
    {
        abort_unless(
            $file->contract_id === $this->contract->id,
            404
        );

        return $contractFileService->download($file);
    }

    public function deleteFile(
        int $id,
        ContractFileService $contractFileService
    ): void {
        $file = ContractFile::findOrFail($id);

        abort_unless(
            $file->contract_id === $this->contract->id,
            404
        );

        $contractFileService->delete($file);

        $this->contract->load([
            'counterparty',
            'addendums',
            'files.uploadedBy',
        ]);

        session()->flash(
            'success',
            'Документ успешно удален.'
        );
    }

    public function deleteAddendum(int $id): void
    {
        $contractAddendum = ContractAddendum::findOrFail($id);

        abort_unless(
            $contractAddendum->contract_id === $this->contract->id,
            404
        );

        $number = $contractAddendum->number;

        $contractAddendum->delete();

        $this->contract->load([
            'counterparty',
            'addendums',
            'files.uploadedBy',
        ]);

        session()->flash(
            'success',
            "Дополнительное соглашение №{$number} успешно удалено."
        );
    }

    public function render()
    {
        return view('livewire.contracts.show');
    }
}
