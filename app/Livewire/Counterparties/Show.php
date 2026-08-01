<?php

namespace App\Livewire\Counterparties;

use App\Models\Counterparty;
use Livewire\Component;

class Show extends Component
{
    public Counterparty $counterparty;

    public function mount(Counterparty $counterparty): void
    {
        $this->counterparty = $counterparty->load([
            'contracts',
        ]);
    }

    public function render()
    {
        return view('livewire.counterparties.show');
    }
}