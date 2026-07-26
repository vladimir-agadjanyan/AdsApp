<div class="card">

    <div class="card-header">
        <h4 class="mb-0">Редактирование договора</h4>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">№ договора</label>
                <input
                    type="text"
                    class="form-control"
                    wire:model.blur="number"
                >
            </div>

            <div class="col-md-6">
                <label class="form-label">Контрагент</label>

                <select
                    class="form-select"
                    wire:model.live="counterparty_id"
                >
                    <option value="">
                        Выберите контрагента
                    </option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Дата подписания
                </label>

                <input
                    type="date"
                    class="form-control"
                    wire:model.live="contract_date"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Дата начала
                </label>

                <input
                    type="date"
                    class="form-control"
                    wire:model.live="start_date"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Дата окончания
                </label>

                <input
                    type="date"
                    class="form-control"
                    wire:model.live="end_date"
                >
            </div>

            <div class="col-md-3">
                <label class="form-label">
                    Стоимость договора
                </label>

                <input
                    type="number"
                    min="0"
                    step="1"
                    class="form-control"
                    wire:model.live="amount"
                >
            </div>

            <div class="col-12">
                <label class="form-label">
                    Примечание
                </label>

                <textarea
                    rows="4"
                    class="form-control"
                    wire:model.live="note"
                ></textarea>
            </div>

        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="mb-1">
                    Дополнительные соглашения
                </h5>

                <p class="text-muted mb-0">
                    Список дополнительных соглашений к договору
                </p>

            </div>

            <a
                href="{{ route('contract-addendums.create', ['contract' => $contract]) }}"
                class="btn btn-primary btn-sm"
                wire:navigate
            >
                + Добавить соглашение
            </a>

        </div>

        @forelse($contract->addendums as $addendum)

            <div class="card mb-3">

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-3">
                            <strong>№ соглашения</strong><br>
                            {{ $addendum->number }}
                        </div>

                        <div class="col-md-3">
                            <strong>Дата подписания</strong><br>
                            {{ $addendum->signed_at?->format('d.m.Y') }}
                        </div>

                        <div class="col-md-3">
                            <strong>Действует до</strong><br>
                            {{ $addendum->end_date?->format('d.m.Y') }}
                        </div>

                        <div class="col-md-3">
                            <strong>Изменение стоимости</strong><br>

                            @if($addendum->amount_change > 0)

                                <span class="text-success fw-semibold">
                                    {{ $addendum->formatted_amount_change }}
                                </span>

                            @elseif($addendum->amount_change < 0)

                                <span class="text-danger fw-semibold">
                                    {{ $addendum->formatted_amount_change }}
                                </span>

                            @else

                                {{ $addendum->formatted_amount_change }}

                            @endif

                        </div>

                    </div>

                    @if($addendum->note)

                        <hr>

                        <div>

                            <label class="form-label fw-semibold">
                                Примечание
                            </label>

                            <div class="border rounded p-3 bg-light">
                                {{ $addendum->note }}
                            </div>

                        </div>

                    @endif

                    <div class="d-flex justify-content-end gap-2 mt-3">

                        <a
                            href="{{ route('contract-addendums.edit', $addendum) }}"
                            class="btn btn-outline-primary btn-sm"
                            wire:navigate
                            title="Редактировать"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            wire:click="deleteAddendum({{ $addendum->id }})"
                            title="Удалить"
                        >
                            <i class="bi bi-trash"></i>
                        </button>

                    </div>

                </div>

            </div>

        @empty

            <div class="alert alert-light border text-center mb-0">
                Дополнительные соглашения отсутствуют.
            </div>

        @endforelse

    <div class="card border-primary mt-4">

        <div class="card-header">
            Финансовая информация
        </div>

        <div class="card-body">

            <div class="d-flex justify-content-between mb-2">

                <span>
                    Стоимость договора
                </span>

                <strong>
                    {{ number_format((float) $amount, 0, ',', ' ') }} сум
                </strong>

            </div>

            <div class="d-flex justify-content-between mb-2">

                <span>
                    Изменения по соглашениям
                </span>

                <strong class="{{ $contract->addendums_amount >= 0 ? 'text-success' : 'text-danger' }}">

                    {{ $contract->addendums_amount > 0 ? '+' : '' }}

                    {{ number_format($contract->addendums_amount, 0, ',', ' ') }} сум

                </strong>

            </div>

            <hr>

            <div class="d-flex justify-content-between fs-5">

                <span>
                    <strong>Итоговая стоимость</strong>
                </span>

                <strong>
                    {{ number_format((float) $amount + $contract->addendums_amount, 0, ',', ' ') }} сум
                </strong>

            </div>

        </div>

    </div>
</div>

<div class="card-footer d-flex justify-content-end gap-2">

    <a
        href="{{ route('contracts.index') }}"
        class="btn btn-secondary"
        wire:navigate
    >
        Отмена
    </a>

    <button
        type="button"
        class="btn btn-primary"
        wire:click="update"
        wire:loading.attr="disabled"
    >
        <span
            wire:loading.remove
            wire:target="update"
        >
            Сохранить изменения
        </span>

        <span
            wire:loading
            wire:target="update"
        >
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status"
            ></span>

            Сохранение...
        </span>
    </button>

</div>

</div>