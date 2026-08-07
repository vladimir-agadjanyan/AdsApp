<?php

namespace App\Livewire\Dashboard;

use App\Models\AdvertisingObject;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PhotoList extends Component
{
    public function render(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Базовый запрос объектов без фотоотчетов
        |--------------------------------------------------------------------------
        */

        $query = AdvertisingObject::query()
            ->whereDoesntHave('photoReports');

        /*
        |--------------------------------------------------------------------------
        | Общее количество объектов без фотоотчетов
        |--------------------------------------------------------------------------
        */

        $objectsWithoutPhotoCount = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Первые 10 объектов для Dashboard
        |--------------------------------------------------------------------------
        */

        $advertisingObjects = $query
            ->with([
                'city',
                'contract.counterparty',
            ])
            ->oldest('created_at')
            ->limit(10)
            ->get();

        $objects = [];

        foreach ($advertisingObjects as $object) {
            $objects[] = [
                'id' => $object->id,

                'name' => $object->name,

                'city' => $object->city->name,

                'counterparty' => $object->contract->counterparty->name,

                'days_without_photo' => (int) $object->created_at
                    ->startOfDay()
                    ->diffInDays(now()->startOfDay()),
            ];
        }

        return view('livewire.dashboard.photo-list', [
            'objects' => $objects,
            'objectsWithoutPhotoCount' => $objectsWithoutPhotoCount,
        ]);
    }
}
