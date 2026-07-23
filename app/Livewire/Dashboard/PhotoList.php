<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class PhotoList extends Component
{
    public array $objects = [
        [
            'name' => 'Billboard №15',
            'city' => 'Ташкент',
            'contractor' => 'Coca-Cola',
            'days_without_photo' => 12,
        ],
        [
            'name' => 'Billboard №15',
            'city' => 'Ташкент',
            'contractor' => 'Coca-Cola',
            'days_without_photo' => 12,
        ],
        [
            'name' => 'Billboard №15',
            'city' => 'Ташкент',
            'contractor' => 'Coca-Cola',
            'days_without_photo' => 12,
        ],
        [
            'name' => 'LED Экран №7',
            'city' => 'Самарканд',
            'contractor' => 'Pepsi',
            'days_without_photo' => 5,
        ],
        [
            'name' => 'LED Экран №7',
            'city' => 'Самарканд',
            'contractor' => 'Pepsi',
            'days_without_photo' => 5,
        ],
        [
            'name' => 'LED Экран №7',
            'city' => 'Самарканд',
            'contractor' => 'Pepsi',
            'days_without_photo' => 5,
        ],
    ];

    public function render()
    {
        return view('livewire.dashboard.photo-list');
    }
}
