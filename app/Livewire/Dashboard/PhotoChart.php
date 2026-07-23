<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class PhotoChart extends Component
{
    public array $chartData = [
        'labels' => [
            'Загружены',
            'Ожидаются',
            'Просрочены',
        ],
        'data' => [
            18,
            7,
            5,
        ],
    ];

    public function render()
    {
        return view('livewire.dashboard.photo-chart');
    }
}
