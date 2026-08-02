<div class="photo-report-show-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            Фотоотчет
        </h2>

        <div class="btn-group">
            @can('update', $photoReport)
                @if($canEdit)
                    <a href="{{ route('photo-reports.edit', $photoReport) }}"  wire:navigate class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Редактировать
                    </a>
                @endif
            @endcan
            <a href="{{ route('photo-reports.index') }}"  wire:navigate class="btn btn-outline-secondary">
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
                        Рекламный объект
                    </label>

                    <div class="fw-semibold">
                        {{ $photoReport->advertisingObject->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Контрагент
                    </label>

                    <div>
                        {{ $photoReport->advertisingObject->contract->counterparty->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Тип рекламы
                    </label>

                    <div>
                        {{ $photoReport->advertisingObject->advertisingType->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Статус
                    </label>

                    <div>
                        <span class="badge text-bg-{{ $photoReport->photoReportStatus->color }}">
                            {{ $photoReport->photoReportStatus->name }}
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Регион
                    </label>

                    <div>
                        {{ $photoReport->advertisingObject->city->region->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Город
                    </label>

                    <div>
                        {{ $photoReport->advertisingObject->city->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Автор
                    </label>

                    <div>
                        {{ $photoReport->createdBy->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-muted">
                        Дата отправки
                    </label>

                    <div>
                        {{ $photoReport->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label text-muted">
                        Комментарий
                    </label>

                    <div>
                        {{ $photoReport->comment ?: '—' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Фотографии --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Фотографии</strong>
        </div>

        <div class="card-body">
            @if($photoReport->photos->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-images fs-1 d-block mb-3"></i>
                    Фотографии отсутствуют.
                </div>
            @else
                <div class="row g-4">
                    @foreach($photoReport->photos as $photo)
                        <div class="col-lg-3 col-md-4 col-sm-6">

                            <div class="card h-100">

                                <img
                                    src="{{ asset('storage/' . $photo->file_path) }}"
                                    class="card-img-top"
                                    style="height:220px;object-fit:cover;"
                                    alt="{{ $photo->original_name }}"
                                >

                                <div class="card-body">
                                    <div class="small text-muted text-truncate">
                                        {{ $photo->original_name }}
                                    </div>
                                </div>

                                <div class="card-footer bg-white">
                                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="bi bi-eye me-1"></i>
                                        Открыть
                                    </a>
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Проверка фотоотчета --}}
    @if($photoReport->photoReportStatus->name === 'На проверке')
        @can('review', $photoReport)
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>Проверка фотоотчета</strong>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label for="reviewComment" class="form-label">
                            Комментарий проверки
                        </label>
                        <textarea id="reviewComment" wire:model="reviewComment" rows="4" class="form-control @error('reviewComment') is-invalid @enderror"
                                   placeholder="Укажите комментарий к проверке">
                        </textarea>
                        @error('reviewComment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            При отклонении фотоотчета комментарий обязателен.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" wire:click="reject" wire:loading.attr="disabled" wire:target="approve,reject" class="btn btn-outline-danger">
                            <span wire:loading.remove wire:target="reject">
                                <i class="bi bi-x-lg me-1"></i>
                                Отклонить
                            </span>

                            <span wire:loading wire:target="reject">
                                Обработка...
                            </span>
                        </button>

                        <button type="button" wire:click="approve" wire:loading.attr="disabled" wire:target="approve,reject" class="btn btn-success">
                            <span wire:loading.remove wire:target="approve">
                                <i class="bi bi-check-lg me-1"></i>
                                Одобрить
                            </span>

                            <span wire:loading wire:target="approve">
                                Обработка...
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        @endcan
    @elseif($photoReport->checked_at)
        {{-- Результат проверки --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Результат проверки</strong>
            </div>

            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            Статус
                        </label>
                        <div>
                            <span class="badge text-bg-{{ $photoReport->photoReportStatus->color }}">
                                {{ $photoReport->photoReportStatus->name }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            Проверил
                        </label>
                        <div>
                            {{ $photoReport->checkedBy?->name ?? '—' }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label text-muted">
                            Дата проверки
                        </label>
                        <div>
                            {{ $photoReport->checked_at?->format('d.m.Y H:i') ?? '—' }}
                        </div>
                    </div>

                    @if($photoReport->review_comment)

                        <div class="col-12">
                            <label class="form-label text-muted">
                                Комментарий проверки
                            </label>
                            <div>
                                {{ $photoReport->review_comment }}
                            </div>
                        </div>

                    @endif

                </div>
            </div>
        </div>
    @endif

</div>