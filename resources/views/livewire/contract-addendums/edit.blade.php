<div class="card">

    <div class="card-header">
        <h4 class="mb-0">
            Редактирование дополнительного соглашения
        </h4>
    </div>

    <div class="card-body">

        <div class="alert alert-light border mb-4">
            <div>
                <strong>Договор:</strong>
                № {{ $contractAddendum->contract->number }}
            </div>

            <div>
                <strong>Контрагент:</strong>
                {{ $contractAddendum->contract->counterparty->name }}
            </div>
        </div>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">№ соглашения</label>

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

            <div class="col-md-6">
                <label class="form-label">Изменение суммы</label>

                <input
                    type="number"
                    step="0.01"
                    class="form-control @error('amount_change') is-invalid @enderror"
                    wire:model.blur="amount_change"
                >

                @error('amount_change')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Дата подписания</label>

                <input
                    type="date"
                    class="form-control @error('signed_at') is-invalid @enderror"
                    wire:model="signed_at"
                >

                @error('signed_at')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Действует до</label>

                <input
                    type="date"
                    class="form-control @error('end_date') is-invalid @enderror"
                    wire:model="end_date"
                >

                @error('end_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Примечание</label>

                <textarea
                    rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    wire:model="note"
                ></textarea>

                @error('note')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

    </div>

    <div class="card-footer d-flex justify-content-end gap-2">

        <a
            href="{{ route('contracts.edit', $contractAddendum->contract) }}"
            class="btn btn-secondary"
            wire:navigate
        >
            Отмена
        </a>

        <button
            type="button"
            class="btn btn-primary"
            wire:click="update"
        >
            Сохранить изменения
        </button>

    </div>

</div>