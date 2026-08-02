<div class="reports-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="section-title mb-1">
                Сводный отчет
            </h2>

            <p class="text-muted mb-0">
                Общая статистика по договорам, рекламным объектам
                и фотоотчетам
            </p>
        </div>

        <div class="d-flex gap-2">

            <button
                type="button"
                class="btn btn-success"
                wire:click="exportExcel"
                wire:loading.attr="disabled"
                wire:target="exportExcel"
            >
                <span
                    wire:loading.remove
                    wire:target="exportExcel"
                >
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Экспорт в Excel
                </span>

                <span
                    wire:loading
                    wire:target="exportExcel"
                >
                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>
                    Формирование...
                </span>
            </button>

            <a
                href="{{ route('reports.index') }}"
                wire:navigate
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                К отчетам
            </a>

        </div>

    </div>

    {{-- Filters --}}
    <x-filters-panel :open="true">

        <div class="row g-3">

            <div class="col-lg-5">
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

            <div class="col-lg-5">
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

            <div class="col-lg-2 d-flex align-items-end">
                <button
                    type="button"
                    class="btn btn-outline-secondary w-100"
                    wire:click="resetFilters"
                >
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Сбросить
                </button>
            </div>

        </div>

    </x-filters-panel>

    {{-- KPI --}}
    <section class="mb-4">

        <div class="row g-4">

            <x-card-stat
                icon="bi-file-earmark-text"
                :value="$contractsCount"
                title="Договоры"
                description="Всего договоров"
            />

            <x-card-stat
                icon="bi-cash-stack"
                :value="number_format($contractsAmount, 0, '.', ' ')"
                title="Сумма договоров"
                description="С учетом допсоглашений"
            />

            <x-card-stat
                icon="bi-geo-alt"
                :value="$advertisingObjectsCount"
                title="Объекты"
                description="Рекламных объектов"
            />

            <x-card-stat
                icon="bi-camera"
                :value="$photoReportsCount"
                title="Фотоотчеты"
                description="Всего фотоотчетов"
            />

        </div>

    </section>

    {{-- Contracts + Photo reports --}}
    <section class="mb-4">

        <div class="row g-4">

            {{-- Contracts --}}
            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Договоры по статусам
                        </h5>
                    </div>

                    <div class="card-body">

                        <div
                            class="d-flex justify-content-between
                                   align-items-center border-bottom py-3"
                        >
                            <div>
                                <span
                                    class="badge bg-success me-2"
                                >
                                    &nbsp;
                                </span>

                                Активные
                            </div>

                            <strong>
                                {{ $contractsByStatus['active'] }}
                            </strong>
                        </div>

                        <div
                            class="d-flex justify-content-between
                                   align-items-center border-bottom py-3"
                        >
                            <div>
                                <span
                                    class="badge bg-warning me-2"
                                >
                                    &nbsp;
                                </span>

                                Скоро заканчиваются
                            </div>

                            <strong>
                                {{ $contractsByStatus['expiring'] }}
                            </strong>
                        </div>

                        <div
                            class="d-flex justify-content-between
                                   align-items-center py-3"
                        >
                            <div>
                                <span
                                    class="badge bg-danger me-2"
                                >
                                    &nbsp;
                                </span>

                                Просрочены
                            </div>

                            <strong>
                                {{ $contractsByStatus['expired'] }}
                            </strong>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Photo reports --}}
            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-camera me-2"></i>
                            Фотоотчеты по статусам
                        </h5>
                    </div>

                    <div class="card-body">

                        @forelse($photoReportsByStatus as $status)

                            <div
                                class="d-flex justify-content-between
                                       align-items-center border-bottom py-3"
                            >
                                <div>
                                    <span
                                        class="badge bg-{{ $status->color }} me-2"
                                    >
                                        {{ $status->name }}
                                    </span>
                                </div>

                                <strong>
                                    {{ $status->photo_reports_count }}
                                </strong>
                            </div>

                        @empty

                            <div class="text-center text-muted py-4">
                                Нет данных
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- Regions + Types --}}
    <section>

        <div class="row g-4">

            {{-- Regions --}}
            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-map me-2"></i>
                            Рекламные объекты по регионам
                        </h5>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Регион</th>

                                    <th class="text-end">
                                        Объекты
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($objectsByRegion as $region)

                                    <tr>
                                        <td>
                                            {{ $region->name }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            {{ $region->advertising_objects_count }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="2"
                                            class="text-center text-muted py-4"
                                        >
                                            Нет данных
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            {{-- Advertising types --}}
            <div class="col-lg-6">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-badge-ad me-2"></i>
                            Рекламные объекты по типам
                        </h5>
                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">
                                <tr>
                                    <th>Тип рекламы</th>

                                    <th class="text-end">
                                        Объекты
                                    </th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($objectsByType as $type)

                                    <tr>
                                        <td>
                                            {{ $type->name }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            {{ $type->advertising_objects_count }}
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td
                                            colspan="2"
                                            class="text-center text-muted py-4"
                                        >
                                            Нет данных
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>