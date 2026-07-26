<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\ContractAddendum;
use Livewire\Component;

class Show extends Component
{
    public Contract $contract;

    public function mount(Contract $contract): void
    {
        $this->contract = $contract->load([
            'counterparty',
            'addendums',
        ]);
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
        return view('livewire.contracts.show');
    }
}