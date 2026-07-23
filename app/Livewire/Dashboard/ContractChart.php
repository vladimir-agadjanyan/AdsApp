<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class ContractChart extends Component
{
    public array $chartData = [
        'active' => 18,
        'expiring' => 5,
        'expired' => 2,
    ];

    public function render()
    {
        return view('livewire.dashboard.contract-chart');
    }
}
