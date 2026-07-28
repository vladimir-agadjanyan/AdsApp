<?php

namespace App\Livewire\ContractAddendums;

use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    public Contract $contract;

    public string $number = '';

    public ?string $signed_at = null;

    public ?string $end_date = null;

    public string $amount_change = '0';

    public ?string $note = null;

    public function mount(Contract $contract): void
    {
        $this->contract = $contract;
    }

    protected function rules(): array
    {
        return [
            'number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contract_addendums')
                    ->where(fn ($query) => $query->where(
                        'contract_id',
                        $this->contract->id
                    )),
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
                'required',
                'numeric',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function save(): void
    {
        $this->contract->addendums()->create([
            ...$this->validate(),
            'created_by' => 1,
        ]);

        session()->flash(
            'success',
            'Дополнительное соглашение успешно создано.'
        );

        $this->redirectRoute(
            'contracts.edit',
            ['contract' => $this->contract],
            navigate: true
        );
    }

    public function render(): View
    {
        return view('livewire.contract-addendums.create');
    }
}
