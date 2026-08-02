<?php

namespace App\Livewire\AdvertisingObjects;

use App\Models\AdvertisingObject;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public AdvertisingObject $advertisingObject;

    public function mount(AdvertisingObject $advertisingObject): void
    {
        $this->authorize(
            'view',
            $advertisingObject
        );

        $this->advertisingObject = $advertisingObject->load([
            'contract.counterparty',
            'city.region',
            'advertisingType',
            'objectStatus',
            'photoReports.photoReportStatus',
            'photoReports.photos',
        ]);
    }

    public function render(): View
    {
        return view('livewire.advertising-objects.show');
    }
}