<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Services\Contract\ContractFileService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public Collection $counterparties;
    public string $number = '';
    public ?int $counterparty_id = null;
    public ?string $contract_date = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $note = null;

    #[Validate([
        'documents.*' => [
            'nullable',
            'file',
            'max:10240',
            'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
        ],
    ])]
    public array $documents = [];

    public function mount(): void
    {
        $this->counterparties = Counterparty::query()
            ->orderBy('name')
            ->get();
    }

    protected function rules(): array
    {
        return [
            'number' => ['required', 'string', 'max:255', 'unique:contracts,number'],
            'counterparty_id' => ['required', 'exists:counterparties,id'],
            'contract_date' => ['required', 'date'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'note' => ['nullable', 'string'],
        ];
    }

    public function save(ContractFileService $contractFileService): void
    {
        $validated = $this->validate();
        $contract = Contract::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        foreach ($this->documents as $document) {
            $contractFileService->upload(contract: $contract, file: $document, uploadedBy: auth()->id());
        }

        session()->flash('success', 'Договор успешно создан.');

        $this->redirectRoute('contracts.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.contracts.create');
    }
}
