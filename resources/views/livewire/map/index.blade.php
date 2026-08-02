<div class="map-page">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">
                Карта
            </h2>

            <p class="text-muted mb-0">
                Расположение рекламных объектов
            </p>
        </div>
    </div>

    <x-filters-panel :open="true">

        <div class="row g-3">

            <div class="col-lg-4">
                <label class="form-label">
                    Регион
                </label>

                <select
                    class="form-select"
                    wire:model.live="regionId"
                >
                    <option value="">Все регионы</option>

                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Город
                </label>

                <select
                    class="form-select"
                    wire:model.live="cityId"
                >
                    <option value="">Все города</option>

                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Контрагент
                </label>

                <select
                    class="form-select"
                    wire:model.live="counterpartyId"
                >
                    <option value="">Все контрагенты</option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row g-3 mt-1">

            <div class="col-lg-4">
                <label class="form-label">
                    Тип рекламы
                </label>

                <select
                    class="form-select"
                    wire:model.live="advertisingTypeId"
                >
                    <option value="">Все типы</option>

                    @foreach($advertisingTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Статус
                </label>

                <select
                    class="form-select"
                    wire:model.live="objectStatusId"
                >
                    <option value="">Все статусы</option>

                    @foreach($objectStatuses as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 d-flex align-items-end">
                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    wire:click="resetFilters"
                >
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Сбросить
                </button>
            </div>

        </div>

    </x-filters-panel>

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <div
                id="objects-map"
                class="map"
                wire:ignore
                style="height: 700px;"
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

        </div>

    </div>

</div>