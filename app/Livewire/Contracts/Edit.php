<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\ContractAddendum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Contract $contract;

    public Collection $counterparties;

    public string $number = '';
    public ?int $counterparty_id = null;
    public ?string $contract_date = null;
    public ?string $start_date = null;
    public ?string $end_date = null;
    public ?string $amount = null;
    public ?string $note = null;

    protected function rules(): array
    {
        return [
            'number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('contracts')
                    ->where(
                        fn ($query) => $query->where(
                            'counterparty_id',
                            $this->counterparty_id
                        )
                    )
                    ->ignore($this->contract),
            ],

            'counterparty_id' => [
                'required',
                'exists:counterparties,id',
            ],

            'contract_date' => [
                'required',
                'date',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function mount(Contract $contract): void
    {
        $this->contract = $contract;

        $this->counterparties = Counterparty::query()
            ->orderBy('name')
            ->get();

        $this->number = $contract->number;
        $this->counterparty_id = $contract->counterparty_id;
        $this->contract_date = $contract->contract_date?->format('Y-m-d');
        $this->start_date = $contract->start_date?->format('Y-m-d');
        $this->end_date = $contract->end_date?->format('Y-m-d');
        $this->amount = (string) $contract->amount;
        $this->note = $contract->note;
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->contract->update($validated);

        session()->flash(
            'success',
            'Договор успешно обновлен.'
        );

        $this->redirectRoute(
            'contracts.index',
            navigate: true
        );
    }

    public function deleteAddendum(int $id): void
    {
        $contractAddendum = ContractAddendum::findOrFail($id);
    
        if ($contractAddendum->contract_id !== $this->contract->id) {
            abort(404);
        }
    
        $number = $contractAddendum->number;
    
        $contractAddendum->delete();
    
        $this->contract->load([
            'counterparty',
            'addendums',
        ]);
    
        session()->flash(
            'success',
            "Дополнительное соглашение №{$number} успешно удалено."
        );
    }

    public function render()
    {
        return view('livewire.contracts.edit');
    }
}