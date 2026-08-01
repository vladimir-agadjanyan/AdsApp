<div class="photo-report-edit-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="section-title mb-0">
            Редактирование фотоотчета
        </h2>

        <a
            href="{{ route('photo-reports.show', $photoReport) }}"
            wire:navigate
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Назад
        </a>

    </div>

    <form wire:submit="save">

        {{-- Основная информация --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>Основная информация</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Регион --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Регион
                        </label>

                        <select
                            class="form-select"
                            wire:model.live="regionId"
                        >
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

                    {{-- Город --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Город
                        </label>

                        <select
                            class="form-select"
                            wire:model.live="cityId"
                        >
                            <option value="">
                                Все города
                            </option>

                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $city->name }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    {{-- Тип рекламы --}}
                    <div class="col-md-4">

                        <label class="form-label">
                            Тип рекламы
                        </label>

                        <select
                            class="form-select"
                            wire:model.live="advertisingTypeId"
                        >
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

                    {{-- Рекламный объект --}}
                    <div class="col-12">

                        <label class="form-label">
                            Рекламный объект
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('advertisingObjectId') is-invalid @enderror"
                            wire:model="advertisingObjectId"
                        >
                            <option value="">
                                Выберите рекламный объект
                            </option>

                            @foreach($advertisingObjects as $object)
                                <option value="{{ $object->id }}">
                                    {{ $object->name }}
                                    @if($object->address)
                                        — {{ $object->address }}
                                    @endif
                                </option>
                            @endforeach

                        </select>

                        @error('advertisingObjectId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Комментарий --}}
                    <div class="col-12">

                        <label class="form-label">
                            Комментарий
                        </label>

                        <textarea
                            class="form-control @error('comment') is-invalid @enderror"
                            wire:model="comment"
                            rows="4"
                            placeholder="Комментарий к фотоотчету..."
                        ></textarea>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>


        {{-- Текущие фотографии --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    Текущие фотографии
                </strong>

                <span class="badge text-bg-secondary">
                    {{ $photoReport->photos->count() }}
                </span>

            </div>

            <div class="card-body">

                @if($photoReport->photos->isNotEmpty())

                    <div class="row g-4">

                        @foreach($photoReport->photos as $photo)

                            <div
                                class="col-lg-3 col-md-4 col-sm-6"
                                wire:key="photo-{{ $photo->id }}"
                            >

                                <div class="card h-100">

                                    <a
                                        href="{{ asset('storage/' . $photo->file_path) }}"
                                        target="_blank"
                                    >
                                        <img
                                            src="{{ asset('storage/' . $photo->file_path) }}"
                                            class="card-img-top"
                                            style="height: 220px; object-fit: cover;"
                                            alt="{{ $photo->original_name }}"
                                        >
                                    </a>

                                    <div class="card-body">

                                        <div
                                            class="small text-muted text-truncate"
                                            title="{{ $photo->original_name }}"
                                        >
                                            {{ $photo->original_name }}
                                        </div>

                                    </div>

                                    <div class="card-footer bg-white">

                                        <div class="d-flex gap-2">

                                            <a
                                                href="{{ asset('storage/' . $photo->file_path) }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-primary flex-grow-1"
                                            >
                                                <i class="bi bi-eye me-1"></i>
                                                Открыть
                                            </a>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                wire:click="deletePhoto({{ $photo->id }})"
                                                wire:confirm="Удалить эту фотографию?"
                                                title="Удалить"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="text-center text-muted py-4">

                        <i class="bi bi-images fs-1 d-block mb-2"></i>

                        Фотографии отсутствуют.

                    </div>

                @endif

            </div>

        </div>


        {{-- Добавление новых фотографий --}}
        <div class="card shadow-sm mb-4">

            <div class="card-header">
                <strong>
                    Добавить фотографии
                </strong>
            </div>

            <div class="card-body">

                <label class="form-label">
                    Новые фотографии
                </label>

                <input
                    type="file"
                    class="form-control @error('photos.*') is-invalid @enderror"
                    wire:model="photos"
                    multiple
                    accept="image/*"
                >

                <div class="form-text">
                    Можно выбрать несколько фотографий.
                    Уже загруженные фотографии останутся без изменений.
                </div>

                @error('photos')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror

                @error('photos.*')
                    <div class="text-danger small mt-2">
                        {{ $message }}
                    </div>
                @enderror


                {{-- Превью новых фотографий --}}
                @if($photos)

                    <div class="mt-4">

                        <div class="fw-semibold mb-3">
                            Новые фотографии
                        </div>

                        <div class="row g-3">

                            @foreach($photos as $index => $photo)

                                <div
                                    class="col-lg-2 col-md-3 col-sm-4"
                                    wire:key="new-photo-{{ $index }}"
                                >

                                    <img
                                        src="{{ $photo->temporaryUrl() }}"
                                        class="img-fluid rounded border"
                                        style="width: 100%; height: 150px; object-fit: cover;"
                                        alt="Новое фото"
                                    >

                                </div>

                            @endforeach

                        </div>

                    </div>

                @endif

                <div wire:loading wire:target="photos" class="text-muted mt-3">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Загрузка фотографий...
                </div>

            </div>

        </div>


        {{-- Кнопки --}}
        <div class="d-flex gap-2">

            <button
                type="submit"
                class="btn btn-primary"
                wire:loading.attr="disabled"
                wire:target="save,photos"
            >
                <span
                    wire:loading.remove
                    wire:target="save"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Сохранить
                </span>

                <span
                    wire:loading
                    wire:target="save"
                >
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Сохранение...
                </span>
            </button>

            <a
                href="{{ route('photo-reports.show', $photoReport) }}"
                wire:navigate
                class="btn btn-outline-secondary"
            >
                Отмена
            </a>

        </div>

    </form>

</div>