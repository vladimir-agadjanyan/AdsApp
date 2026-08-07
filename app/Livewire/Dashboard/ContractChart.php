<?php

namespace App\Livewire\Dashboard;

use App\Models\Contract;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ContractChart extends Component
{
    /**
     * @var array{
     *     active: int,
     *     expiring: int,
     *     expired: int
     * }
     */
    public array $chartData = [
        'active' => 0,
        'expiring' => 0,
        'expired' => 0,
    ];

    public function mount(): void
    {
        $today = today();
        $expiringDate = today()->addDays(30);

        $this->chartData = [
            'active' => Contract::query()
                ->whereDate('end_date', '>', $expiringDate)
                ->count(),

            'expiring' => Contract::query()
                ->whereDate('end_date', '>=', $today)
                ->whereDate('end_date', '<=', $expiringDate)
                ->count(),

            'expired' => Contract::query()
                ->whereDate('end_date', '<', $today)
                ->count(),
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard.contract-chart');
    }
}
