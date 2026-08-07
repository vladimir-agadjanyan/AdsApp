<?php

namespace App\Livewire\Dashboard;

use App\Models\AdvertisingObject;
use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\PhotoReport;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Index extends Component
{
    public function render(): View
    {
        $advertisingObjects = AdvertisingObject::query()
            ->with([
                'contract.counterparty',
                'city.region',
                'advertisingType',
                'objectStatus',
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('livewire.dashboard.index', [
            'advertisingObjects' => $advertisingObjects,
            'contractsCount' => Contract::query()->count(),
            'advertisingObjectsCount' => AdvertisingObject::query()->count(),
            'photoReportsCount' => PhotoReport::query()
                ->whereHas('photoReportStatus', function ($query) {
                    $query->where('name', 'На проверке');
                })
                ->count(),

            'counterpartiesCount' => Counterparty::query()->count(),
        ])->layout('layouts.app');
    }
}
