<?php

namespace App\Livewire\Counterparties;

use App\DTO\Counterparties\UpdateCounterpartyData;
use App\Models\Counterparty;
use App\Services\CounterpartyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public Counterparty $counterparty;

    public string $name = '';

    public string $inn = '';

    public string $contact_person = '';

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $note = '';

    public function mount(Counterparty $counterparty): void
    {
        $this->authorize('update', $counterparty);
        $this->counterparty = $counterparty;
        $this->name = $counterparty->name;
        $this->inn = $counterparty->inn;
        $this->contact_person = $counterparty->contact_person;
        $this->phone = $counterparty->phone;
        $this->email = $counterparty->email ?? '';
        $this->address = $counterparty->address ?? '';
        $this->note = $counterparty->note ?? '';
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('counterparties', 'name')
                    ->ignore($this->counterparty),
            ],

            'inn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('counterparties', 'inn')
                    ->ignore($this->counterparty),
            ],

            'contact_person' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function save(CounterpartyService $service): void
    {
        $this->authorize('update', $this->counterparty);

        $this->validate();

        $data = new UpdateCounterpartyData(
            name: $this->name,
            inn: $this->inn,
            phone: $this->phone,
            email: $this->email ?: null,
            address: $this->address ?: null,
            contactPerson: $this->contact_person,
            note: $this->note ?: null,
        );

        $this->counterparty = $service->update($this->counterparty, $data);

        session()->flash('success', 'Контрагент успешно обновлен.');

        $this->redirectRoute('counterparties.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.counterparties.edit');
    }
}
