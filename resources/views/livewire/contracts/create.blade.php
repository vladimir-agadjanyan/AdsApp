<div class="card">

    <div class="card-header">
        <h4 class="mb-0">Создание договора</h4>
    </div>

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">№ договора</label>
                <input type="text" class="form-control" wire:model.blur="number">
            </div>

            <div class="col-md-6">
                <label class="form-label">Контрагент</label>
                <select class="form-select" wire:model="counterparty_id">
                    <option value="">Выберите контрагента</option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Дата подписания</label>
                <input type="date" class="form-control" wire:model="contract_date">
            </div>

            <div class="col-md-4">
                <label class="form-label">Дата начала</label>
                <input type="date" class="form-control" wire:model="start_date">
            </div>

            <div class="col-md-4">
                <label class="form-label">Дата окончания</label>
                <input type="date" class="form-control" wire:model="end_date">
            </div>

            <div class="col-12">
                <label class="form-label">Примечание</label>
                <textarea class="form-control" wire:model="note" rows="4"></textarea>
            </div>

        </div>

    </div>

    <div class="card-footer d-flex justify-content-end gap-2">

        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
            Отмена
        </a>

        <button type="button" class="btn btn-primary" wire:click="save">
            Сохранить
        </button>

    </div>

</div>