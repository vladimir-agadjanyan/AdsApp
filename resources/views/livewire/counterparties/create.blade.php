<div class="counterparties-page">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Новый контрагент
            </h1>

            <p class="text-body-secondary mb-0">
                Заполните информацию о новом контрагенте.
            </p>
        </div>

        <a
            href="{{ route('counterparties.index') }}"
            wire:navigate
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Назад
        </a>

    </div>

    <form wire:submit="save">

        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="row g-4">

                    {{-- Название --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Название компании
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            wire:model.blur="name"
                        >

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- ИНН --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            ИНН
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('inn') is-invalid @enderror"
                            wire:model.blur="inn"
                        >

                        @error('inn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Контактное лицо --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Контактное лицо
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('contact_person') is-invalid @enderror"
                            wire:model.blur="contact_person"
                        >

                        @error('contact_person')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Телефон --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Телефон
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('phone') is-invalid @enderror"
                            wire:model.blur="phone"
                        >

                        @error('phone')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            wire:model.blur="email"
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Адрес --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Адрес
                        </label>

                        <input
                            type="text"
                            class="form-control @error('address') is-invalid @enderror"
                            wire:model.blur="address"
                        >

                        @error('address')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Примечание --}}
                    <div class="col-12">

                        <label class="form-label">
                            Примечание
                        </label>

                        <textarea
                            rows="4"
                            class="form-control @error('note') is-invalid @enderror"
                            wire:model.blur="note"
                        ></textarea>

                        @error('note')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="card-footer bg-white border-0">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('counterparties.index') }}"
                        wire:navigate
                        class="btn btn-outline-secondary"
                    >
                        Отмена
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >
                        <span
                            wire:loading.remove
                            wire:target="save"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Создать
                        </span>

                        <span
                            wire:loading
                            wire:target="save"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                            ></span>

                            Создание...
                        </span>
                    </button>

                </div>

            </div>

        </div>

    </form>

</div>