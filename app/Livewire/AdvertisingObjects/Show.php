<?php

namespace App\Livewire\AdvertisingObjects;

use App\Models\AdvertisingObject;
use Livewire\Component;

class Show extends Component
{
    public AdvertisingObject $advertisingObject;

    public function mount(AdvertisingObject $advertisingObject): void
    {
        $this->advertisingObject = $advertisingObject->load([
            'contract.counterparty',
            'city.region',
            'advertisingType',
            'objectStatus',
            'photoReports.photoReportStatus',
            'photoReports.photos',
        ]);
    }

    public function render()
    {
        return view('livewire.advertising-objects.show');
    }
}