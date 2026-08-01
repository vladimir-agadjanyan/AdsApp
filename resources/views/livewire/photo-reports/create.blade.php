<div class="photo-report-create-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Создание фотоотчета</h2>

        <a
            href="{{ route('photo-reports.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Назад
        </a>
    </div>

    <form wire:submit="save">

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="row g-3">

                    {{-- Регион --}}
                    <div class="col-lg-6">
                        <label class="form-label">
                            Регион
                        </label>

                        <select
                            class="form-select @error('regionId') is-invalid @enderror"
                            wire:model.live="regionId"
                        >
                            <option value="">Выберите регион</option>

                            @foreach($regions as $region)
                                <option value="{{ $region->id }}">
                                    {{ $region->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('regionId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Город --}}
                    <div class="col-lg-6">
                        <label class="form-label">
                            Город
                        </label>

                        <select
                            class="form-select @error('cityId') is-invalid @enderror"
                            wire:model.live="cityId"
                        >
                            <option value="">Выберите город</option>

                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">
                                    {{ $city->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('cityId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Тип рекламы --}}
                    <div class="col-lg-6">
                        <label class="form-label">
                            Тип рекламы
                        </label>

                        <select
                            class="form-select @error('advertisingTypeId') is-invalid @enderror"
                            wire:model.live="advertisingTypeId"
                        >
                            <option value="">Выберите тип</option>

                            @foreach($advertisingTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('advertisingTypeId')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Рекламный объект --}}
                    <div class="col-lg-6">
                        <label class="form-label">
                            Рекламный объект
                        </label>

                        <select
                            class="form-select @error('advertisingObjectId') is-invalid @enderror"
                            wire:model="advertisingObjectId"
                        >
                            <option value="">Выберите рекламный объект</option>

                            @foreach($advertisingObjects as $object)
                                <option value="{{ $object->id }}">
                                    {{ $object->name }}
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
                            rows="4"
                            class="form-control @error('comment') is-invalid @enderror"
                            wire:model="comment"
                        ></textarea>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Фотографии --}}
                    <div class="col-12">
                        <label class="form-label">
                            Фотографии
                        </label>

                        <input
                            type="file"
                            multiple
                            class="form-control @error('photos') is-invalid @enderror"
                            wire:model="photos"
                        >

                        @error('photos')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('photos.*')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Предпросмотр --}}
                    @if($photos)

                        <div class="col-12">

                            <div class="row g-3">

                                @foreach($photos as $photo)

                                    <div class="col-lg-2 col-md-3 col-4">

                                        <img
                                            src="{{ $photo->temporaryUrl() }}"
                                            class="img-fluid rounded border"
                                        >

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif

                </div>

            </div>

            <div class="card-footer d-flex justify-content-end gap-2">

                <a
                    href="{{ route('photo-reports.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Отмена
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-check-lg me-1"></i>
                    Отправить
                </button>

            </div>

        </div>

    </form>

</div>