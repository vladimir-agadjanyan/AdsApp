<?php

namespace App\Livewire\Counterparties;

use App\Models\Counterparty;
use App\Services\CounterpartyService;
use Livewire\Component;
use Illuminate\Validation\Rule;


class Create extends Component
{
    public string $name = '';
    public string $inn = '';
    public string $phone = '';
    public string $email = '';
    public string $contact_person = '';
    public string $address = '';
    public string $note = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('counterparties', 'name')],
            'inn' => ['required', 'string', 'max:20', Rule::unique('counterparties', 'name')],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ];
    }

    public function save(CounterpartyService $service): void
    {
        $validated = $this->validate();

        $service->create($validated);

        session()->flash(
            'success',
            'Контрагент успешно создан.'
        );

        $this->redirectRoute('counterparties.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.counterparties.create');
    }
}