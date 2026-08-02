<div class="reports-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="section-title mb-1">
                Отчет по договорам
            </h2>

            <p class="text-muted mb-0">
                Анализ договоров, сроков действия и контрагентов
            </p>
        </div>

        <a href="{{ route('reports.index') }}" wire:navigate class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            К отчетам
        </a>
    </div>

    {{-- Filters --}}
    <x-filters-panel :open="true">
        <div class="row g-3">
            {{-- Counterparty --}}
            <div class="col-lg-3">

                <label class="form-label">
                    Контрагент
                </label>

                <select class="form-select" wire:model.live="counterpartyId">
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

            {{-- Status --}}
            <div class="col-lg-3">
                <label class="form-label">
                    Статус
                </label>

                <select class="form-select" wire:model.live="status">
                    <option value="">
                        Все статусы
                    </option>

                    <option value="active">
                        Активные
                    </option>

                    <option value="expiring">
                        Истекающие
                    </option>

                    <option value="expired">
                        Истёкшие
                    </option>
                </select>
            </div>

            {{-- Date from --}}
            <div class="col-lg-2">
                <label class="form-label">
                    Окончание от
                </label>

                <input type="date" class="form-control" wire:model.live="dateFrom">
            </div>

            {{-- Date to --}}
            <div class="col-lg-2">
                <label class="form-label">
                    Окончание до
                </label>

                <input type="date" class="form-control" wire:model.live="dateTo">
            </div>

            {{-- Reset --}}
            <div class="col-lg-2 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Сбросить
                </button>
            </div>
        </div>
    </x-filters-panel>

    {{-- Result header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted">
            Найдено договоров:
            <strong class="text-body">
                {{ $contracts->count() }}
            </strong>
        </div>
        {{-- Экспорт добавим следующим этапом --}}
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
                        <th>№ договора</th>
                        <th>Контрагент</th>
                        <th>Дата договора</th>
                        <th>Начало</th>
                        <th>Окончание</th>
                        <th class="text-end">Сумма</th>
                        <th class="text-end">Итого</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr wire:key="contract-{{ $contract->id }}">
                            <td>
                                <a href="{{ route('contracts.show', $contract) }}"  wire:navigate class="text-decoration-none fw-semibold">
                                    №{{ $contract->number }}
                                </a>
                            </td>
                            <td>
                                {{ $contract->counterparty->name }}
                            </td>
                            <td>
                                {{ $contract->contract_date?->format('d.m.Y') ?? '—' }}
                            </td>
                            <td>
                                {{ $contract->start_date?->format('d.m.Y') ?? '—' }}
                            </td>
                            <td>
                                {{ $contract->end_date?->format('d.m.Y') ?? '—' }}
                            </td>
                            <td class="text-end text-nowrap">
                                {{ number_format((float) $contract->amount, 2, ',',  ' ' ) }}
                            </td>
                            <td class="text-end text-nowrap fw-semibold">
                                {{ number_format($contract->total_amount, 2, ',',  ' ' ) }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $contract->status_class }}">
                                    {{ $contract->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Договоры по выбранным фильтрам не найдены.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>