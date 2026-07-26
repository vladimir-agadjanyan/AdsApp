<?php

namespace App\Livewire\ContractAddendums;

use App\Models\ContractAddendum;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public ContractAddendum $contractAddendum;

    public string $number = '';
    public ?string $signed_at = null;
    public ?string $end_date = null;
    public ?string $note = null;
    public ?float $amount_change = null;

    public function mount(ContractAddendum $contractAddendum): void
    {
        $this->contractAddendum = $contractAddendum;
        $this->number = $contractAddendum->number;
        $this->amount_change = $contractAddendum->amount_change;
        $this->signed_at = $contractAddendum->signed_at?->format('Y-m-d');
        $this->end_date = $contractAddendum->end_date?->format('Y-m-d');
        $this->note = $contractAddendum->note;
    }

    protected function rules(): array
    {
        return [
            'number' => [
                'bail',
                'required',
                'string',
                'max:255',
                Rule::unique('contract_addendums')
                    ->where(fn ($query) => $query->where(
                        'contract_id',
                        $this->contractAddendum->contract_id
                    ))
                    ->ignore($this->contractAddendum->id),
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

            'note' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'amount_change' => [
                'nullable',
                'numeric',
            ],
        ];
    }

    protected function attributes(): array
    {
        return [
            'number' => 'номер соглашения',
            'signed_at' => 'дата подписания',
            'end_date' => 'дата окончания',
            'note' => 'примечание',
            'amount_change' => 'изменение суммы',
        ];
    }
    protected function messages(): array
    {
        return [
            'number.required' => 'Укажите номер соглашения.',
            'number.unique' => 'Дополнительное соглашение с таким номером уже существует для данного договора.',
            'amount_change.required' => 'Укажите изменение суммы.',
            'amount_change.numeric' => 'Изменение суммы должно быть числом.',
            'signed_at.required' => 'Укажите дату подписания.',
            'end_date.required' => 'Укажите дату окончания.',
            'end_date.after_or_equal' => 'Дата окончания не может быть раньше даты подписания.',
        ];
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->contractAddendum->update($validated);

        session()->flash(
            'success',
            'Дополнительное соглашение успешно обновлено.'
        );

        $this->redirectRoute(
            'contracts.edit',
            ['contract' => $this->contractAddendum->contract],
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.contract-addendums.edit');
    }
}