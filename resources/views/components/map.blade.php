@props([
    'advertisingObjects',
])

<x-dashboard-card title="">

    <div
        id="map"
        class="map"
        wire:ignore
        data-objects="{{ $advertisingObjects->map(fn ($object) => [
            'id' => $object->id,
            'name' => $object->name,
            'latitude' => $object->latitude,
            'longitude' => $object->longitude,
            'counterparty' => $object->contract->counterparty->name,
            'region' => $object->city->region->name,
            'city' => $object->city->name,
            'type' => $object->advertisingType->name,
            'status' => $object->objectStatus->name,
            'url' => route('advertising-objects.show', $object),
        ])->values()->toJson() }}"
    ></div>

</x-dashboard-card>