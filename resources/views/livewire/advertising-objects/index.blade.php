<div class="advertising-objects-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            Рекламные объекты
        </h2>
    </div>

    {{-- Кнопка + поиск --}}
    <div class="row align-items-center mb-3">

        @can('create', \App\Models\AdvertisingObject::class)
            <div class="col-lg-auto mb-3 mb-lg-0">
                <a href="{{ route('advertising-objects.create') }}" wire:navigate class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>
                    Новый объект
                </a>
            </div>
        @endcan

        <div class="col-lg-5">
            <div class="input-group">
                <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Поиск по названию, адресу, договору...">
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
                <label class="form-label">Тип рекламы</label>

                <select class="form-select" wire:model.live="advertisingTypeId">
                    <option value="">
                        Все типы
                    </option>
                    @foreach($advertisingTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Контрагент</label>

                <select class="form-select" wire:model.live="counterpartyId">
                    <option value="">
                        Все контрагенты
                    </option>
                    @foreach($counterparties as $counterparty)
                        <option value="{{ $counterparty->id }}">
                            {{ $counterparty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Статус</label>

                <select class="form-select" wire:model.live="objectStatusId">
                    <option value="">
                        Все статусы
                    </option>
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
                <label class="form-label">Регион</label>

                <select class="form-select" wire:model.live="regionId">
                    <option value="">
                        Все регионы
                    </option>

                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">Город</label>

                <select class="form-select" wire:model.live="cityId" @disabled(!$regionId)>
                    <option value="">
                        {{ $regionId ? 'Все города' : 'Сначала выберите регион' }}
                    </option>

                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 d-flex align-items-end">
                <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Сбросить
                </button>
            </div>
        </div>

    </x-filters-panel>

    {{-- Таблица --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Название</th>
                    <th>Контрагент</th>
                    <th>Регион</th>
                    <th>Город</th>
                    <th>Тип</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>

            <tbody>
                @forelse($advertisingObjects as $object)
                    <tr>
                        {{-- Название --}}
                        <td>
                            <a href="{{ route('advertising-objects.show', $object) }}" wire:navigate>
                                {{ $object->name }}
                            </a>
                        </td>

                        {{-- Контрагент --}}
                        <td>
                            {{ $object->contract->counterparty->name }}
                        </td>

                        {{-- Регион --}}
                        <td>
                            {{ $object->city->region->name }}
                        </td>

                        {{-- Город --}}
                        <td>
                            {{ $object->city->name }}
                        </td>

                        {{-- Тип --}}
                        <td>
                            {{ $object->advertisingType->name }}
                        </td>

                        {{-- Статус --}}
                        <td>
                            <span class="badge text-bg-{{ $object->objectStatus->color }}">
                                {{ $object->objectStatus->name }}
                            </span>
                        </td>

                        {{-- Действия --}}
                        <td class="text-start">
                            <a href="{{ route('advertising-objects.show', $object) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="Просмотр">
                                <i class="bi bi-eye"></i>
                            </a>

                            @can('update', $object)
                                <a href="{{ route('advertising-objects.edit', $object) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="Редактировать">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan

                            @can('delete', $object)
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="confirmDelete({{ $object->id }})" title="Удалить">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-pin-map fs-1 text-secondary d-block mb-3"></i>

                            <h5 class="mb-2">
                                Рекламные объекты не найдены
                            </h5>

                            <p class="text-muted mb-0">
                                Измените параметры поиска или создайте новый объект.
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Модальное окно удаления --}}
    @if($showDeleteModal)

        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0, 0, 0, .5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Удалить рекламный объект
                        </h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">
                            Вы действительно хотите удалить рекламный объект:
                        </p>
                        <p class="fw-semibold mb-3">
                            {{ $advertisingObjectToDelete?->name }}
                        </p>
                        @if($advertisingObjectToDelete?->contract?->counterparty)
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
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">
                            Отмена
                        </button>

                        <button type="button" class="btn btn-danger" wire:click="delete">
                            <i class="bi bi-trash me-1"></i>
                            Удалить
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Пагинация --}}
    <x-pagination :paginator="$advertisingObjects" />

</div>