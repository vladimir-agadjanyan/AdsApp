<div class="reports-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                Отчет по рекламным объектам
            </h2>

            <p class="text-muted mb-0">
                Анализ рекламных объектов по регионам, контрагентам,
                типам рекламы и статусам
            </p>
        </div>

        <a
            href="{{ route('reports.index') }}"
            wire:navigate
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            К отчетам
        </a>

    </div>

    {{-- Filters --}}
    <x-filters-panel :open="true">

        <div class="row g-3">

            {{-- Region --}}
            <div class="col-lg-4">

                <label class="form-label">
                    Регион
                </label>

                <select
                    class="form-select"
                    wire:model.live="regionId"
                >
                    <option value="">
                        Все регионы
                    </option>

                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- City --}}
            <div class="col-lg-4">

                <label class="form-label">
                    Город
                </label>

                <select
                    class="form-select"
                    wire:model.live="cityId"
                >
                    <option value="">
                        Все города
                    </option>

                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- Counterparty --}}
            <div class="col-lg-4">

                <label class="form-label">
                    Контрагент
                </label>

                <select
                    class="form-select"
                    wire:model.live="counterpartyId"
                >
                    <option value="">
                        Все контрагенты
                    </option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>

            </div>

        </div>

        <div class="row g-3 mt-1">

            {{-- Advertising type --}}
            <div class="col-lg-4">

                <label class="form-label">
                    Тип рекламы
                </label>

                <select
                    class="form-select"
                    wire:model.live="advertisingTypeId"
                >
                    <option value="">
                        Все типы
                    </option>

                    @foreach($advertisingTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- Status --}}
            <div class="col-lg-4">

                <label class="form-label">
                    Статус
                </label>

                <select
                    class="form-select"
                    wire:model.live="objectStatusId"
                >
                    <option value="">
                        Все статусы
                    </option>

                    @foreach($objectStatuses as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- Reset --}}
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

    {{-- Result header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="text-muted">
            Найдено объектов:
            <strong class="text-body">
                {{ $advertisingObjects->count() }}
            </strong>
        </div>

        <button type="button" class="btn btn-success" wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel">
            <span wire:loading.remove wire:target="exportExcel">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Экспорт в Excel
            </span>

            <span wire:loading wire:target="exportExcel">
                Формирование...
            </span>
        </button>

    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Объект</th>
                        <th>Договор</th>
                        <th>Контрагент</th>
                        <th>Регион</th>
                        <th>Город</th>
                        <th>Адрес</th>
                        <th>Тип рекламы</th>
                        <th>Статус</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($advertisingObjects as $object)

                        <tr wire:key="advertising-object-{{ $object->id }}">

                            <td>
                                <a
                                    href="{{ route('advertising-objects.show', $object) }}"
                                    wire:navigate
                                    class="text-decoration-none fw-semibold"
                                >
                                    {{ $object->name }}
                                </a>
                            </td>

                            <td class="text-nowrap">
                                №{{ $object->contract->number }}
                            </td>

                            <td>
                                {{ $object->contract->counterparty->name }}
                            </td>

                            <td>
                                {{ $object->city->region->name }}
                            </td>

                            <td>
                                {{ $object->city->name }}
                            </td>

                            <td>
                                {{ $object->address }}
                            </td>

                            <td>
                                {{ $object->advertisingType->name }}
                            </td>

                            <td>
                                <span class="badge bg-{{ $object->objectStatus->color }}">
                                    {{ $object->objectStatus->name }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="8"
                                class="text-center text-muted py-5"
                            >
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                Рекламные объекты по выбранным фильтрам
                                не найдены.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>