<div class="advertising-objects-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Рекламные объекты</h2>
    </div>

    {{-- Поиск + кнопка --}}
    <div class="row align-items-center mb-3">
        <div class="col-lg-auto mb-3 mb-lg-0">
            <a href="{{ route('advertising-objects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Новый объект
            </a>
        </div>
        <div class="col-lg-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Поиск..." wire:model.live.debounce.300ms="search">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
            </div>
        </div>
    </div>

    {{-- Фильтры --}}
    <x-filters-panel>

        <div class="row g-3">

            <div class="col-lg-4">
                <label class="form-label">
                    Тип рекламы
                </label>

                <select
                    class="form-select"
                    wire:model.live="advertisingTypeId"
                >
                    <option value="">Все типы</option>

                    @foreach($advertisingTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Контрагент
                </label>

                <select
                    class="form-select"
                    wire:model.live="counterpartyId"
                >
                    <option value="">Все контрагенты</option>

                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Статус
                </label>

                <select
                    class="form-select"
                    wire:model.live="objectStatusId"
                >
                    <option value="">Все статусы</option>

                    @foreach($objectStatuses as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="row g-3 mt-3">

            <div class="col-lg-4">
                <label class="form-label">
                    Регион
                </label>

                <select
                    class="form-select"
                    wire:model.live="regionId"
                >
                    <option value="">Все регионы</option>

                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>



            <div class="col-lg-4">
                <label class="form-label">
                    Город
                </label>

                <select
                    class="form-select"
                    wire:model.live="cityId"
                >
                    <option value="">Все города</option>

                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 d-flex align-items-end">
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

    </x-filters-panel>

    <div class="table-responsive">

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Контрагент</th>
                    <th>Регион</th>
                    <th>Город</th>
                    <th>Тип</th>
                    <th>Статус</th>
                    <th width="120">Действия</th>
                </tr>
            </thead>
            <tbody>

            @forelse($advertisingObjects as $object)

                <tr>

                    <td>
                        <a href="{{ route('advertising-objects.show', $object) }}" wire:navigate>
                            {{ $object->name }}
                        </a>
                    </td>
                    <td>{{ $object->contract->counterparty->name }}</td>
                    <td>{{ $object->city->region->name }}</td>
                    <td>{{ $object->city->name }}</td>
                    <td>{{ $object->advertisingType->name }}</td>
                    <td>
                        <span class="badge text-bg-{{ $object->objectStatus->color }}">
                            {{ $object->objectStatus->name }}
                        </span>
                    </td>
                    <td>

                        <div class="btn-group">

                            <a href="{{ route('advertising-objects.edit', $object) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $object->id }})">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        Рекламные объекты не найдены.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if ($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Удалить рекламный объект
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="cancelDelete"
                        ></button>
                    </div>

                    <div class="modal-body">

                        <p class="mb-2">
                            Вы действительно хотите удалить рекламный объект:
                        </p>

                        <p class="fw-semibold mb-3">
                            {{ $advertisingObjectToDelete?->name }}
                        </p>

                        @if ($advertisingObjectToDelete?->contract?->counterparty)
                            <p class="text-muted mb-3">
                                Контрагент:
                                {{ $advertisingObjectToDelete->contract->counterparty->name }}
                            </p>
                        @endif

                        <div class="alert alert-warning mb-0">
                            Действие необратимо.
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            wire:click="cancelDelete"
                        >
                            Отмена
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger"
                            wire:click="delete"
                        >
                            <i class="bi bi-trash me-1"></i>
                            Удалить
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @endif

    <x-pagination :paginator="$advertisingObjects"/>

</div>