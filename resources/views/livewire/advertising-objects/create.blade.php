<div class="advertising-objects-create-page">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Создание рекламного объекта</h1>

        <a
            href="{{ route('advertising-objects.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Назад
        </a>
    </div>

    <form wire:submit="save">

        <div class="card shadow-sm">

            <div class="card-header">
                Основная информация
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Контрагент</label>

                        <select class="form-select" wire:model.live="counterpartyId">
                            <option value="">Выберите контрагента</option>

                            @foreach($counterparties as $counterparty)
                                <option value="{{ $counterparty->id }}">
                                    {{ $counterparty->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('counterpartyId')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Договор</label>

                        <select
                            class="form-select"
                            wire:model.live="contract_id"
                            wire:key="contracts-{{ $counterpartyId }}"
                        >
                            <option value="">Выберите договор</option>

                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}">
                                    {{ $contract->number }}
                                </option>
                            @endforeach
                        </select>

                        @error('contract_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Название объекта
                        </label>

                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            wire:model="name"
                        >

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Тип рекламы
                        </label>

                        <select
                            class="form-select @error('advertising_type_id') is-invalid @enderror"
                            wire:model="advertising_type_id"
                        >
                            <option value="">Выберите тип</option>

                            @foreach($advertisingTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('advertising_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Статус
                        </label>

                        <select
                            class="form-select @error('object_status_id') is-invalid @enderror"
                            wire:model="object_status_id"
                        >
                            <option value="">Выберите статус</option>

                            @foreach($objectStatuses as $status)
                                <option value="{{ $status->id }}">
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('object_status_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">

            <div class="card-header"> Местоположение</div>

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">
                            Регион
                        </label>

                        <select class="form-select @error('regionId') is-invalid @enderror" wire:model.live="regionId">
                            <option value="">Выберите регион</option>

                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('regionId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Город</label>

                        <select class="form-select @error('city_id') is-invalid @enderror" wire:model="city_id">
                            <option value="">Выберите город</option>

                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('city_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">Адрес</label>

                        <input type="text" class="form-control @error('address') is-invalid @enderror" wire:model="address">

                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label"> Широта</label>

                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" wire:model="latitude">

                        @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Долгота</label>

                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" wire:model="longitude">

                        @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm mt-4">

            <div class="card-header">
                Дополнительно
            </div>

            <div class="card-body">

                <label class="form-label">
                    Примечание
                </label>

                <textarea
                    rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    wire:model="note"
                ></textarea>

                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <a href="{{ route('advertising-objects.index') }}" class="btn btn-secondary">
                Отмена
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i>
                Сохранить
            </button>

        </div>

    </form>

</div>