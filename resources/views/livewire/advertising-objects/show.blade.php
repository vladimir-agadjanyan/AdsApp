<div class="advertising-object-show-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="section-title mb-0">
            Рекламный объект
        </h2>

        <div class="btn-group">

            <a
                href="{{ route('advertising-objects.edit', $advertisingObject) }}"
                wire:navigate
                class="btn btn-primary"
            >
                <i class="bi bi-pencil me-1"></i>
                Редактировать
            </a>

            <a
                href="{{ route('advertising-objects.index') }}"
                wire:navigate
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Назад
            </a>

        </div>

    </div>

    {{-- Основная информация --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>Основная информация</strong>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Название
                    </label>

                    <div class="fw-semibold">
                        {{ $advertisingObject->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Контрагент
                    </label>

                    <div>
                        {{ $advertisingObject->contract->counterparty->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Тип рекламы
                    </label>

                    <div>
                        {{ $advertisingObject->advertisingType->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Статус
                    </label>

                    <div>
                        <span class="badge text-bg-{{ $advertisingObject->objectStatus->color }}">
                            {{ $advertisingObject->objectStatus->name }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Регион
                    </label>

                    <div>
                        {{ $advertisingObject->city->region->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Город
                    </label>

                    <div>
                        {{ $advertisingObject->city->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Адрес
                    </label>

                    <div>
                        {{ $advertisingObject->address }}
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">
                        Широта
                    </label>

                    <div>
                        {{ $advertisingObject->latitude }}
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label text-muted">
                        Долгота
                    </label>

                    <div>
                        {{ $advertisingObject->longitude }}
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted">
                        Примечание
                    </label>

                    <div>
                        {{ $advertisingObject->note ?: '—' }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Фотоотчеты --}}
    <div class="card shadow-sm">

        <div class="card-header">
            <strong>Фотоотчеты</strong>
        </div>

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>
                        <th>Дата</th>
                        <th>Статус</th>
                        <th class="text-center">
                            Фото
                        </th>
                        <th width="120">
                            Действия
                        </th>
                    </tr>

                </thead>

                <tbody>

                @forelse($advertisingObject->photoReports as $photoReport)

                    <tr>

                        <td>
                            {{ $photoReport->created_at->format('d.m.Y') }}
                        </td>

                        <td>

                            <span class="badge text-bg-{{ $photoReport->photoReportStatus->color }}">
                                {{ $photoReport->photoReportStatus->name }}
                            </span>

                        </td>

                        <td class="text-center">
                            {{ $photoReport->photos->count() }}
                        </td>

                        <td>

                            <a
                                href="{{ route('photo-reports.show', $photoReport) }}"
                                wire:navigate
                                class="btn btn-sm btn-outline-primary"
                            >
                                <i class="bi bi-eye"></i>
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center py-5">

                            <i class="bi bi-images fs-1 text-secondary d-block mb-3"></i>

                            <h5 class="mb-2">
                                Фотоотчетов пока нет
                            </h5>

                            <p class="text-muted mb-0">
                                Для данного рекламного объекта еще не создано ни одного фотоотчета.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>