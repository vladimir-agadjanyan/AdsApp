<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Counterparty;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Create extends Component
{
    public Collection $counterparties;

    public string $number = '';
    public ?int $counterparty_id = null;
    public ?string $contract_date = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $note = null;

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

    public function save(): void
    {
        $validated = $this->validate();

        Contract::create([
            ...$validated,
            'created_by' => 1,
        ]);

        session()->flash(
            'success',
            'Договор успешно создан.'
        );

        $this->redirectRoute(
            'contracts.index',
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.contracts.create');
    }
}