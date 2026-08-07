<?php

namespace App\Livewire\Contracts;

use App\DTO\ContractAddendums\CreateContractAddendumData;
use App\DTO\ContractAddendums\UpdateContractAddendumData;
use App\Models\Contract;
use App\Models\ContractFile;
use App\Services\Contract\ContractAddendumService;
use App\Services\Contract\ContractFileService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Addendums extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public Contract $contract;

    public bool $showForm = false;

    public ?int $editingAddendumId = null;

    public string $number = '';

    public ?string $signed_at = null;

    public ?string $end_date = null;

    public ?string $amount_change = null;

    public ?string $note = null;

    public $document = null;

    public function mount(Contract $contract): void
    {
        $this->contract = $contract;
        $this->loadAddendums();
    }

    protected function rules(): array
    {
        return [
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contract_addendums')
                    ->where(
                        fn ($query) => $query->where(
                            'contract_id',
                            $this->contract->id
                        )
                    )
                    ->ignore($this->editingAddendumId),
            ],

            'signed_at' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:signed_at',
            ],

            'amount_change' => [
                'nullable',
                'numeric',
            ],

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'document' => [
                'nullable',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'number.required' => 'Укажите номер соглашения.',

            'number.unique' => 'Дополнительное соглашение с таким номером уже существует.',

            'signed_at.required' => 'Укажите дату подписания.',

            'end_date.required' => 'Укажите дату окончания.',

            'end_date.after_or_equal' => 'Дата окончания не может быть раньше даты подписания.',

            'amount_change.numeric' => 'Изменение стоимости должно быть числом.',

            'document.file' => 'Не удалось загрузить документ.',

            'document.max' => 'Размер документа не должен превышать 10 МБ.',

            'document.mimes' => 'Допустимые форматы: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.',
        ];
    }

    public function create(): void
    {
        $this->authorize('update', $this->contract);
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id, ContractAddendumService $service): void
    {
        $this->authorize('update', $this->contract);

        $addendum = $service->findForContract($this->contract, $id);

        $this->editingAddendumId = $addendum->id;
        $this->number = $addendum->number;

        $this->signed_at = $addendum
            ->signed_at
            ?->format('Y-m-d');

        $this->end_date = $addendum
            ->end_date
            ?->format('Y-m-d');

        $this->amount_change = (string) $addendum->amount_change;
        $this->note = $addendum->note;
        $this->document = null;
        $this->showForm = true;
        $this->resetValidation();
    }

    public function save(ContractAddendumService $addendumService, ContractFileService $fileService): void
    {
        $this->authorize('update', $this->contract);

        $this->validate();

        $userId = (int) Auth::id();

        if ($this->editingAddendumId) {

            $addendum = $addendumService->findForContract(
                $this->contract,
                $this->editingAddendumId,
            );

            $data = new UpdateContractAddendumData(
                number: $this->number,
                signedAt: $this->signed_at,
                endDate: $this->end_date,
                amountChange: (float) ($this->amount_change ?? 0),
                note: $this->note,
            );

            $addendum = $addendumService->update($addendum, $data);

            if ($this->document) {
                $fileService->upload(
                    contract: $this->contract,
                    file: $this->document,
                    contractAddendumId: $addendum->id,
                    uploadedBy: $userId,
                );
            }

            session()->flash(
                'success',
                'Дополнительное соглашение успешно обновлено.'
            );

        } else {

            $data = new CreateContractAddendumData(
                contractId: $this->contract->id,
                number: $this->number,
                signedAt: $this->signed_at,
                endDate: $this->end_date,
                amountChange: (float) ($this->amount_change ?? 0),
                note: $this->note,
                createdBy: $userId,
            );

            $addendum = $addendumService->create($data);

            if ($this->document) {
                $fileService->upload(
                    contract: $this->contract,
                    file: $this->document,
                    contractAddendumId: $addendum->id,
                    uploadedBy: $userId,
                );
            }

            session()->flash('success', 'Дополнительное соглашение успешно добавлено.');
        }

        $this->resetForm();
        $this->loadAddendums();
    }

    public function downloadDocument(int $fileId, ContractFileService $fileService)
    {
        $file = $this->findAddendumFile($fileId);

        return $fileService->download($file);
    }

    public function deleteDocument(int $fileId, ContractFileService $fileService): void
    {
        $this->authorize('update', $this->contract);

        $file = $this->findAddendumFile($fileId);
        $fileService->delete($file);

        $this->loadAddendums();

        session()->flash('success', 'Документ дополнительного соглашения удалён.');
    }

    public function delete(int $id, ContractAddendumService $service): void
    {
        $this->authorize('update', $this->contract);

        $addendum = $service->findForContract($this->contract, $id);

        $service->delete($addendum);

        if ($this->editingAddendumId === $id) {
            $this->resetForm();
        }

        $this->loadAddendums();

        session()->flash('success', 'Дополнительное соглашение успешно удалено.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function findAddendumFile(int $fileId): ContractFile
    {
        return ContractFile::query()
            ->where('contract_id', $this->contract->id)
            ->whereNotNull('contract_addendum_id')
            ->findOrFail($fileId);
    }

    private function resetForm(): void
    {
        $this->reset([
            'showForm',
            'editingAddendumId',
            'number',
            'signed_at',
            'end_date',
            'amount_change',
            'note',
            'document',
        ]);

        $this->resetValidation();
    }

    private function loadAddendums(): void
    {
        $this->contract->load([
            'addendums.files.uploadedBy',
        ]);
    }

    public function render()
    {
        return view('livewire.contracts.addendums');
    }
}
