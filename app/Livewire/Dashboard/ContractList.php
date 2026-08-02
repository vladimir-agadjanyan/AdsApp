<?php

namespace App\Livewire\Dashboard;

use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContractList extends Component
{
    public function render(): View
    {
        $contracts = Contract::query()
            ->with('counterparty')
            ->whereDate('end_date', '>=', today())
            ->whereDate('end_date', '<=', today()->addDays(30))
            ->orderBy('end_date')
            ->get();

        return view('livewire.dashboard.contract-list', [
            'contracts' => $contracts,
        ]);
    }
}