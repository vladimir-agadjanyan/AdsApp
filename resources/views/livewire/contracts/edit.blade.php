<div class="card">

    <div class="card-header">
        <h4 class="mb-0">
            Редактирование договора
        </h4>
    </div>

    <div class="card-body">
        {{-- Основные данные договора --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">№ договора</label>
                <input type="text" class="form-control @error('number') is-invalid @enderror" wire:model.blur="number">
                @error('number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Контрагент</label>

                <select class="form-select @error('counterparty_id') is-invalid @enderror" wire:model.live="counterparty_id">
                    <option value="">
                        Выберите контрагента
                    </option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>

                @error('counterparty_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="col-md-3">
                <label class="form-label">Дата подписания</label>
                <input type="date" class="form-control @error('contract_date') is-invalid @enderror" wire:model.live="contract_date">
                @error('contract_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Дата начала</label>
                <input type="date" class="form-control @error('start_date') is-invalid @enderror" wire:model.live="start_date">
                @error('start_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Дата окончания</label>
                <input type="date" class="form-control @error('end_date') is-invalid @enderror" wire:model.live="end_date">
                @error('end_date')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-md-3">
                <label class="form-label">Стоимость договора</label>
                <div class="input-group">
                    <input type="number" min="0" step="1" class="form-control @error('amount') is-invalid @enderror" wire:model.live="amount">
                    <span class="input-group-text"> сум </span>
                    @error('amount')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Примечание</label>
                <textarea rows="4" class="form-control @error('note') is-invalid @enderror" wire:model.live="note"></textarea>
                @error('note')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        {{-- Документы основного договора --}}
        <hr class="my-4">
        <livewire:contracts.documents :contract="$contract" :key="'contract-documents-'.$contract->id"/>

        {{-- Дополнительные соглашения --}}
        <hr class="my-4">
        <livewire:contracts.addendums :contract="$contract" :key="'contract-addendums-'.$contract->id"/>

         {{-- Финансовая информация --}}
        <div class="card border-primary mt-4">
            <div class="card-header">
                <strong>Финансовая информация </strong>
            </div>

            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Стоимость договора</span>
                    <strong> {{ number_format((float) $amount, 0, ',',  ' ' ) }}  сум </strong>
                </div>
            
                <div class="d-flex justify-content-between mb-2">
                    <span>Изменения по соглашениям</span>
                    <strong class="{{ $contract->addendums_amount >= 0 ? 'text-success' : 'text-danger'}}">
                        @if($contract->addendums_amount > 0)
                            +
                        @endif
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

    {{-- Нижние кнопки --}}
    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('contracts.index') }}" class="btn btn-secondary" wire:navigate>
            Отмена
        </a>

        <button type="button" class="btn btn-primary" wire:click="update" wire:loading.attr="disabled" wire:target="update">
            <span wire:loading.remove wire:target="update">
                <i class="bi bi-check-lg me-1"></i>
                Сохранить изменения
            </span>

            <span wire:loading wire:target="update">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Сохранение...
            </span>
        </button>
    </div>

</div>