<?php

namespace App\Livewire\Counterparties;

use App\Models\Counterparty;
use App\Services\CounterpartyService;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
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
            'name' => ['required', 'string', 'max:255', Rule::unique('counterparties', 'name')->ignore($this->counterparty)],
            'inn' => ['required', 'string', 'max:255', Rule::unique('counterparties', 'inn')->ignore($this->counterparty)],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email','max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ];
    }

    public function save(CounterpartyService $service): void
    {
        $validated = $this->validate();

        $service->update($this->counterparty, $validated);

        session()->flash('success', 'Контрагент успешно обновлен.');

        $this->redirectRoute('counterparties.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.counterparties.edit');
    }
}