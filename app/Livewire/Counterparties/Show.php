<?php

namespace App\Livewire\Counterparties;

use App\Models\Counterparty;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Counterparty $counterparty;

    public function mount(Counterparty $counterparty): void
    {
        $this->authorize('view', $counterparty);

        $this->counterparty = $counterparty->load([
            'contracts',
        ]);
    }

    public function render()
    {
        return view('livewire.counterparties.show');
    }
}
