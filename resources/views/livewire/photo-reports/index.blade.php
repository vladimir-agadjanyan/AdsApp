<div class="photo-reports-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Фотоотчеты</h2>
    </div>

    {{-- Поиск + кнопка --}}
    <div class="row align-items-center mb-3">

        <div class="col-lg-auto mb-3 mb-lg-0">
            <a href="{{ route('photo-reports.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Новый фотоотчет
            </a>
        </div>

        <div class="col-lg-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Поиск по рекламному объекту..." wire:model.live.debounce.300ms="search">
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
                    Регион
                </label>

                <select class="form-select" wire:model.live="regionId">
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

                <select class="form-select" wire:model.live="cityId">
                    <option value="">Все города</option>

                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">
                            {{ $city->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Тип рекламы</label>

                <select class="form-select" wire:model.live="advertisingTypeId">
                    <option value="">Все</option>

                    @foreach ($advertisingTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4">
                <label class="form-label">
                    Статус
                </label>

                <select class="form-select" wire:model.live="photoReportStatusId">
                    <option value="">Все статусы</option>

                    @foreach($photoReportStatuses as $status)
                        <option value="{{ $status->id }}">
                            {{ $status->name }}
                        </option>
                    @endforeach

                </select>
            </div>



            <div class="col-lg-8">
                <label class="form-label">
                    Период отправки от-до
                </label>

                <div class="row g-2">
                    <div class="col">
                        <input
                            type="date"
                            class="form-control"
                            wire:model.live="dateFrom"
                        >
                    </div>

                    <div class="col">
                        <input
                            type="date"
                            class="form-control"
                            wire:model.live="dateTo"
                        >
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-3 mt-3">

            <div class="col-lg-9 d-flex align-items-end">
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
                    <th>Рекламный объект</th>
                    <th>Город</th>
                    <th>Дата отправки</th>
                    <th class="text-center">Фото</th>
                    <th>Статус</th>
                    <th width="120">Действия</th>
                </tr>
            </thead>

            <tbody>

            @forelse($photoReports as $photoReport)

                <tr>

                    <td>
                        <a href="{{ route('photo-reports.show', $photoReport) }}" wire:navigate>
                            {{ $photoReport->advertisingObject->name }}
                        </a>
                    </td>

                    <td>
                        {{ $photoReport->advertisingObject->city->name }}
                    </td>

                    <td>
                        {{ $photoReport->created_at->format('d.m.Y') }}
                    </td>

                    <td class="text-center">
                        {{ $photoReport->photos_count }}
                    </td>

                    <td>
                        <span class="badge text-bg-{{ $photoReport->photoReportStatus->color }}">
                            {{ $photoReport->photoReportStatus->name }}
                        </span>

                    </td>

                    <td>

                            <a href="{{ route('photo-reports.show', $photoReport) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="Просмотр">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('photo-reports.edit', $photoReport) }}" wire:navigate class="btn btn-sm btn-outline-primary" title="Редактировать">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button" class="btn btn-sm btn-outline-danger"  wire:click="confirmDelete({{ $photoReport->id }})" title="Удалить">
                                <i class="bi bi-trash"></i>
                            </button>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        Фотоотчеты не найдены.
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
                            Удалить фотоотчет
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="cancelDelete"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">
                            Вы действительно хотите удалить фотоотчет:
                        </p>

                        <p class="fw-semibold mb-3">
                            № {{ $photoReportToDelete?->id }}
                        </p>

                        @if ($photoReportToDelete?->advertisingObject)
                            <p class="text-muted mb-3">
                                Рекламный объект:
                                {{ $photoReportToDelete->advertisingObject->name }}
                            </p>
                        @endif

                        <div class="alert alert-warning mb-0">
                            Будут также удалены все фотографии этого фотоотчета.
                            <br>
                            <strong>Действие необратимо.</strong>
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

    <x-pagination :paginator="$photoReports"/>

</div>