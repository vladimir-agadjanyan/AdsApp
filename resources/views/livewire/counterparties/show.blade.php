<div class="counterparty-show-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="section-title mb-0">
            Контрагент
        </h2>

        <div class="btn-group">

            <a
                href="{{ route('counterparties.edit', $counterparty) }}"
                wire:navigate
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Редактировать
            </a>

            <a
                href="{{ route('counterparties.index') }}"
                wire:navigate
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Назад
            </a>

        </div>

    </div>

    {{-- Основная информация --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>Основная информация</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Название
                    </label>

                    <div class="fw-semibold">
                        {{ $counterparty->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        ИНН
                    </label>

                    <div>
                        {{ $counterparty->inn }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Телефон
                    </label>

                    <div>
                        {{ $counterparty->phone ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Email
                    </label>

                    <div>
                        {{ $counterparty->email ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Контактное лицо
                    </label>

                    <div>
                        {{ $counterparty->contact_person ?: '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Адрес
                    </label>

                    <div>
                        {{ $counterparty->address ?: '—' }}
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted">
                        Примечание
                    </label>

                    <div>
                        {{ $counterparty->note ?: '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Договоры --}}
    <div class="card shadow-sm">

        <div class="card-header">
            <strong>Договоры</strong>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>№ договора</th>
                        <th>Дата договора</th>
                        <th>Начало</th>
                        <th>Окончание</th>
                        <th>Статус</th>
                        <th width="120">Действия</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($counterparty->contracts as $contract)

                    <tr>

                        <td>

                            <a
                                href="{{ route('contracts.show', $contract) }}"
                                wire:navigate
                            >
                                {{ $contract->number }}
                            </a>

                        </td>

                        <td>
                            {{ $contract->contract_date?->format('d.m.Y') }}
                        </td>

                        <td>
                            {{ $contract->start_date?->format('d.m.Y') }}
                        </td>

                        <td>
                            {{ $contract->end_date?->format('d.m.Y') }}
                        </td>

                        <td>

                            <span class="badge bg-{{ $contract->statusClass }}">
                                {{ $contract->statusLabel }}
                            </span>

                        </td>

                        <td>

                            <a
                                href="{{ route('contracts.show', $contract) }}"
                                wire:navigate
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="bi bi-eye"></i>
                            </a>

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
                                У данного контрагента отсутствуют договоры.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>