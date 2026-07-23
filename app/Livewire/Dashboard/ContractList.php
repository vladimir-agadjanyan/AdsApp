<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class ContractList extends Component
{
    public array $contracts = [
        [
            'number' => '245',
            'company' => 'Coca-Cola',
            'end_date' => '12.08.2026',
            'days_left' => 5,
        ],
        [
            'number' => '198',
            'company' => 'Pepsi',
            'end_date' => '15.08.2026',
            'days_left' => 8,
        ],
        [
            'number' => '311',
            'company' => 'Uzum',
            'end_date' => '21.08.2026',
            'days_left' => 14,
        ],
        [
            'number' => '127',
            'company' => 'Artel',
            'end_date' => '24.08.2026',
            'days_left' => 17,
        ],
        [
            'number' => '451',
            'company' => 'Nestlé',
            'end_date' => '30.08.2026',
            'days_left' => 23,
        ],
        [
            'number' => '451',
            'company' => 'Nestlé',
            'end_date' => '30.08.2026',
            'days_left' => 23,
        ],
        [
            'number' => '451',
            'company' => 'Nestlé',
            'end_date' => '30.08.2026',
            'days_left' => 23,
        ],
        [
            'number' => '451',
            'company' => 'Nestlé',
            'end_date' => '30.08.2026',
            'days_left' => 23,
        ],
        [
            'number' => '451',
            'company' => 'Nestlé',
            'end_date' => '30.08.2026',
            'days_left' => 23,
        ],
    ];

    public function render()
    {
        return view('livewire.dashboard.contract-list');
    }
}
