<div class="card">

    <div class="card-header">
        <h4 class="mb-0">
            Новое дополнительное соглашение
        </h4>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Договор
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="№ {{ $contract->number }}"
                    disabled
                >

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Контрагент
                </label>

                <input
                    type="text"
                    class="form-control"
                    value="{{ $contract->counterparty->name }}"
                    disabled
                >

            </div>

        </div>

        <div class="row g-3 mt-1">

            <div class="col-md-3">

                <label class="form-label">
                    № соглашения
                </label>

                <input
                    type="text"
                    class="form-control @error('number') is-invalid @enderror"
                    wire:model.blur="number"
                >

                @error('number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-3">

                <label class="form-label">
                    Дата подписания
                </label>

                <input
                    type="date"
                    class="form-control @error('signed_at') is-invalid @enderror"
                    wire:model.live="signed_at"
                >

                @error('signed_at')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-3">

                <label class="form-label">
                    Действует до
                </label>

                <input
                    type="date"
                    class="form-control @error('end_date') is-invalid @enderror"
                    wire:model.live="end_date"
                >

                @error('end_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-3">

                <label class="form-label">
                    Изменение стоимости
                </label>

                <input
                    type="number"
                    step="1"
                    class="form-control @error('amount_change') is-invalid @enderror"
                    wire:model.live="amount_change"
                >

                @error('amount_change')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-text">
                    Положительное значение увеличивает стоимость,
                    отрицательное — уменьшает.
                </div>

            </div>

        </div>

        <div class="mt-4">

            <label class="form-label">
                Примечание
            </label>

            <textarea
                rows="5"
                class="form-control @error('note') is-invalid @enderror"
                wire:model.live="note"
            ></textarea>

            @error('note')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

    </div>
    <div class="card-footer d-flex justify-content-end gap-2">

        <a
            href="{{ route('contracts.edit', $contract) }}"
            class="btn btn-secondary"
            wire:navigate
        >
            Отмена
        </a>

        <button
            type="button"
            class="btn btn-primary"
            wire:click="save"
            wire:loading.attr="disabled"
        >

            <span
                wire:loading.remove
                wire:target="save"
            >
                Сохранить
            </span>

            <span
                wire:loading
                wire:target="save"
            >

                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status"
                    aria-hidden="true"
                ></span>

                Сохранение...

            </span>

        </button>

    </div>

</div>