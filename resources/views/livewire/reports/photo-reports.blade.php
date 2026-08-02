<div class="reports-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                Отчет по фотоотчетам
            </h2>

            <p class="text-muted mb-0">
                Анализ фотоотчетов и результатов их проверки
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

            {{-- Status --}}
            <div class="col-lg-3">

                <label class="form-label">
                    Статус
                </label>

                <select
                    class="form-select"
                    wire:model.live="photoReportStatusId"
                >
                    <option value="">
                        Все статусы
                    </option>

                    @foreach($photoReportStatuses as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>

            </div>

            {{-- Date from --}}
            <div class="col-lg-3">

                <label class="form-label">
                    Создан от
                </label>

                <input
                    type="date"
                    class="form-control"
                    wire:model.live="dateFrom"
                >

            </div>

            {{-- Date to --}}
            <div class="col-lg-3">

                <label class="form-label">
                    Создан до
                </label>

                <input
                    type="date"
                    class="form-control"
                    wire:model.live="dateTo"
                >

            </div>

            {{-- Reset --}}
            <div class="col-lg-3 d-flex align-items-end">

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
            Найдено фотоотчетов:
            <strong class="text-body">
                {{ $photoReports->count() }}
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
                        <th>Контрагент</th>
                        <th>Регион</th>
                        <th>Город</th>
                        <th>Создан</th>
                        <th>Статус</th>
                        <th>Создал</th>
                        <th>Проверил</th>
                        <th>Дата проверки</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($photoReports as $photoReport)

                        <tr wire:key="photo-report-{{ $photoReport->id }}">

                            <td>
                                {{ $photoReport->advertisingObject->name }}
                            </td>

                            <td>
                                {{ $photoReport->advertisingObject
                                    ->contract
                                    ->counterparty
                                    ->name }}
                            </td>

                            <td>
                                {{ $photoReport->advertisingObject
                                    ->city
                                    ->region
                                    ->name }}
                            </td>

                            <td>
                                {{ $photoReport->advertisingObject
                                    ->city
                                    ->name }}
                            </td>

                            <td class="text-nowrap">
                                {{ $photoReport->created_at?->format('d.m.Y H:i') ?? '—' }}
                            </td>

                            <td>
                                <span
                                    class="badge bg-{{ $photoReport->photoReportStatus->color }}"
                                >
                                    {{ $photoReport->photoReportStatus->name }}
                                </span>
                            </td>

                            <td>
                                {{ $photoReport->createdBy?->name ?? '—' }}
                            </td>

                            <td>
                                {{ $photoReport->checkedBy?->name ?? '—' }}
                            </td>

                            <td class="text-nowrap">
                                {{ $photoReport->checked_at?->format('d.m.Y H:i') ?? '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="9"
                                class="text-center text-muted py-5"
                            >
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>

                                Фотоотчеты по выбранным фильтрам не найдены.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>