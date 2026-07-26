<x-dashboard-card>
    <div x-data="{ showFilters: false }" class="contracts-page">
        {{-- Заголовок --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">
                Договоры
            </h2>
        </div>

        {{-- Поиск + кнопка --}}
        <div class="row align-items-center mb-3">

            {{-- Кнопка --}}
            <div class="col-lg-auto mb-3 mb-lg-0">
                <a href="{{ route('contracts.create') }}" wire:navigate class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Новый договор
                </a>
            </div>

            {{-- Поиск --}}
            <div class="col-lg-5">
                <div class="input-group">
                    <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Поиск по номеру договора...">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                </div>
            </div>

        </div>

        {{-- Кнопка фильтров --}}
        <div class="mb-4">
            <button type="button" class="btn btn-filters d-inline-flex align-items-center" @click="showFilters = !showFilters">
                <i class="bi me-2" :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                Фильтры
            </button>
        </div>

        {{-- Фильтры --}}
        <div x-show="showFilters" x-transition.duration.200ms x-cloak class="card border-0 bg-light mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-lg-4">
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
                    <div class="col-lg-3">
                        <label class="form-label">
                            Статус
                        </label>

                        <select class="form-select"  wire:model.live="status">

                            <option value="">
                                Все статусы
                            </option>

                            <option value="active">
                                Активные
                            </option>

                            <option value="expiring">
                                Истекают
                            </option>

                            <option value="expired">
                                Истекли
                            </option>

                        </select>

                    </div>

                </div>

                <div class="row g-3 mt-3">

                    <div class="col-lg-3">

                        <label class="form-label">
                            Дата договора от
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            wire:model.live="contractDateFrom"
                        >

                    </div>

                    <div class="col-lg-3">

                        <label class="form-label">
                            Дата договора до
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            wire:model.live="contractDateTo"
                        >

                    </div>

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

            </div>

        </div>

        {{-- Таблица --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>№ договора</th>
                        <th>Контрагент</th>
                        <th>Дата договора</th>
                        <th>Начало</th>
                        <th>Окончание</th>
                        <th>Статусы</th>
                        <th>Действия</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($contracts as $contract)
                        <tr>
                            <td>
                                <a href="{{ route('contracts.show', $contract) }}" wire:navigate>
                                    {{ $contract->number }}
                                </a>
                            </td>
                            <td>{{ $contract->counterparty->name }}</td>
                            <td>{{ $contract->contract_date?->format('d.m.Y') }}</td>
                            <td>{{ $contract->start_date?->format('d.m.Y') }}</td>
                            <td>{{ $contract->end_date?->format('d.m.Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $contract->statusClass }}">
                                    {{ $contract->statusLabel }}
                                </span>
                            </td>
                            <td class="text-start m-auto">
                                <a href="{{ route('contracts.edit', $contract) }}" wire:navigate class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger"
                                    wire:click="delete({{ $contract->id }})"
                                    wire:confirm="Вы действительно хотите удалить договор №{{ $contract->number }}?

                                    Будут также удалены:
                                    • все дополнительные соглашения;
                                    • все файлы договора.

                                    Это действие необратимо."
                            >
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-folder2-open fs-1 text-secondary d-block mb-3"></i>
                                <h5 class="mb-2">
                                    Договоров пока нет
                                </h5>
                                <p class="text-muted mb-0">
                                    Создайте первый договор.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        {{-- Пагинация --}}
        <x-pagination :paginator="$contracts" />
    </div>
</x-dashboard-card>