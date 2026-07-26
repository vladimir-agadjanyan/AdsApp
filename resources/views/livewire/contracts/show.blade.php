<div class="card mb-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h3 class="mb-0">
            Договор № {{ $contract->number }}
        </h3>

        <div class="d-flex gap-2">

            <span class="badge {{ $contract->status_class }}">
                {{ $contract->status_label }}
            </span>

            <a
                href="{{ route('contracts.edit', $contract) }}"
                class="btn btn-primary"
                wire:navigate
            >
                <i class="bi bi-pencil-square me-1"></i>
                Редактировать
            </a>

            <a
                href="{{ route('contracts.index') }}"
                class="btn btn-secondary"
                wire:navigate
            >
                Назад
            </a>

        </div>

    </div>

    <div class="card-body">

        <div class="row g-4">

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Контрагент
                </label>

                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    {{ $contract->counterparty->name }}
                </div>

            </div>

            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Дата договора
                </label>

                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    {{ $contract->contract_date?->format('d.m.Y') }}
                </div>

            </div>

            <div class="col-md-3">

                <label class="form-label fw-semibold">
                    Стоимость договора
                </label>

                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    {{ number_format($contract->amount, 0, ',', ' ') }} сум
                </div>

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Начало действия
                </label>

                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    {{ $contract->start_date?->format('d.m.Y') }}
                </div>

            </div>

            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Окончание действия
                </label>

                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                    {{ $contract->end_date?->format('d.m.Y') }}
                </div>

            </div>

            <div class="col-12">

                <label class="form-label fw-semibold">
                    Примечание
                </label>

                <div class="border rounded p-3 bg-light" style="min-height: 90px;">

                    @if($contract->note)

                        {{ $contract->note }}

                    @else

                        <span class="text-muted">Примечание отсутствует.</span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class="card mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-1">
                    Дополнительные соглашения
                </h4>

                <small class="text-muted">
                    Список дополнительных соглашений к договору
                </small>

            </div>

            <a
                href="{{ route('contract-addendums.create', ['contract' => $contract]) }}"
                class="btn btn-primary btn-sm"
                wire:navigate
            >
                <i class="bi bi-plus-lg me-1"></i>
                Добавить
            </a>

        </div>

        <div class="card-body">

            @forelse($contract->addendums as $addendum)

                <div class="card mb-3 shadow-sm">

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    № соглашения
                                </label>

                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    {{ $addendum->number }}
                                </div>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Дата подписания
                                </label>

                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    {{ $addendum->signed_at?->format('d.m.Y') }}
                                </div>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Действует до
                                </label>

                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">
                                    {{ $addendum->end_date?->format('d.m.Y') }}
                                </div>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Изменение стоимости
                                </label>

                                <div class="form-control-plaintext border rounded px-3 py-2 bg-light">

                                    @if($addendum->amount_change > 0)

                                        <span class="text-success fw-bold">
                                            {{ $addendum->formatted_amount_change }} сум
                                        </span>

                                    @elseif($addendum->amount_change < 0)

                                        <span class="text-danger fw-bold">
                                            {{ $addendum->formatted_amount_change }} сум
                                        </span>

                                    @else

                                        {{ $addendum->formatted_amount_change }} сум

                                    @endif

                                </div>

                            </div>

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Примечание
                                </label>

                                <div class="border rounded p-3 bg-light" style="min-height:80px;">

                                    @if($addendum->note)

                                        {{ $addendum->note }}

                                    @else

                                        <span class="text-muted">
                                            Примечание отсутствует.
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">

                            <a
                                href="{{ route('contract-addendums.edit', $addendum) }}"
                                class="btn btn-outline-primary btn-sm"
                                wire:navigate
                            >
                                <i class="bi bi-pencil-square me-1"></i>
                                Редактировать
                            </a>

                            <button type="button" class="btn btn-outline-danger btn-sm" wire:click="deleteAddendum({{ $addendum->id }})"
                                wire:confirm="Вы действительно хотите удалить дополнительное соглашение №{{ $addendum->number }}? Это действие необратимо."
                            >
                                <i class="bi bi-trash me-1"></i>
                                Удалить
                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div class="alert alert-light border text-center mb-0">

                    Дополнительные соглашения отсутствуют.

                </div>

            @endforelse

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            <h4 class="mb-0">
                Финансовая информация
            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-8">

                    <div class="mb-3 d-flex justify-content-between">

                        <span>
                            Стоимость договора
                        </span>

                        <strong>
                            {{ number_format($contract->amount, 0, ',', ' ') }} сум
                        </strong>

                    </div>

                    <div class="mb-3 d-flex justify-content-between">

                        <span>
                            Изменения по соглашениям
                        </span>

                        <strong class="{{ $contract->addendums_amount >= 0 ? 'text-success' : 'text-danger' }}">

                            @if($contract->addendums_amount > 0)
                                +
                            @endif

                            {{ number_format($contract->addendums_amount, 0, ',', ' ') }} сум

                        </strong>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">
                            Итоговая стоимость
                        </h4>

                        <h3 class="mb-0">

                            {{ number_format($contract->total_amount, 0, ',', ' ') }}
                            сум

                        </h3>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
