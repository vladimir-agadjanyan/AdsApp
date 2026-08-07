<?php

namespace App\Livewire\Contracts;

use App\DTO\Contracts\UpdateContractData;
use App\Models\Contract;
use App\Models\ContractAddendum;
use App\Models\Counterparty;
use App\Services\Contract\ContractService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

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
        $this->authorize('update', $contract);
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

    public function update(ContractService $contractService): void
    {
        $this->authorize('update', $this->contract);

        $this->validate();

        $data = new UpdateContractData(
            counterpartyId: $this->counterparty_id,
            number: $this->number,
            contractDate: $this->contract_date,
            startDate: $this->start_date,
            endDate: $this->end_date,
            amount: (float) $this->amount,
            note: $this->note,
        );

        $this->contract = $contractService->update($this->contract, $data);

        session()->flash('success', 'Договор успешно обновлен.');

        $this->redirectRoute('contracts.index', navigate: true);
    }

    public function deleteAddendum(int $id): void
    {
        $this->authorize('update', $this->contract);
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

        session()->flash('success', "Дополнительное соглашение №{$number} успешно удалено.");
    }

    public function render()
    {
        return view('livewire.contracts.edit');
    }
}
