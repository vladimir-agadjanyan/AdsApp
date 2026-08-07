<?php

namespace App\Livewire\Counterparties;

use App\DTO\Counterparties\CreateCounterpartyData;
use App\Models\Counterparty;
use App\Services\CounterpartyService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Create extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public string $inn = '';
    public string $phone = '';
    public string $email = '';
    public string $contact_person = '';
    public string $address = '';
    public string $note = '';

    public function mount(): void
    {
        $this->authorize('create', Counterparty::class);
    }

    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('counterparties', 'name'),
            ],

            'inn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('counterparties', 'inn'),
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
        $this->authorize('create', Counterparty::class);
        $this->validate();

        $data = new CreateCounterpartyData(
            name: $this->name,
            inn: $this->inn,
            phone: $this->phone,
            email: $this->email ?: null,
            address: $this->address ?: null,
            contactPerson: $this->contact_person,
            note: $this->note ?: null,
        );

        $service->create($data);

        session()->flash('success','Контрагент успешно создан.');

        $this->redirectRoute('counterparties.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.counterparties.create');
    }
}