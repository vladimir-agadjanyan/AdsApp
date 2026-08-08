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

            <div class="col-md-4">
                <label class="form-label">Сумма договора</label>
                <input type="number" step="0.01" min="0"clsass="form-control @error('amount') is-invalid @enderror" wire:model.blur="amount" placeholder="Введите сумму">

                @error('amount')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Примечание</label>
                <textarea class="form-control" rows="4" wire:model="note"></textarea>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3">
            Документы
        </h5>

        <div class="mb-3">
            <input type="file" class="form-control @error('documents.*') is-invalid @enderror" wire:model="documents" multiple>
            <div class="form-text">
                Можно выбрать сразу несколько файлов (PDF, Word, Excel, изображения).
            </div>
            @error('documents.*')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
            <div class="form-text" wire:loading wire:target="documents">
                Загрузка файлов...
            </div>
        </div>

        @if (!empty($documents))
            <div class="table-responsive">
                <table class="table table-sm table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Файл</th>
                            <th width="150">Размер</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($documents as $document)
                            <tr>
                                <td>
                                    <i class="bi bi-file-earmark me-2"></i>
                                    {{ $document->getClientOriginalName() }}
                                </td>
                                <td>
                                    {{ \Illuminate\Support\Number::fileSize($document->getSize()) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
            Отмена
        </a>

        <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="save">
                Сохранить
            </span>
            <span wire:loading wire:target="save">
                Сохранение...
            </span>
        </button>
    </div>

</div>