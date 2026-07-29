<div class="counterparties-page">
    <form wire:submit="save">
        <div class="row g-3">

            <div class="col-md-6">
                <label for="name" class="form-label">
                    Название компании <span class="text-danger">*</span>
                </label>

                <input
                    id="name"
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    wire:model.blur="name"
                >

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="inn" class="form-label">
                    ИНН <span class="text-danger">*</span>
                </label>

                <input
                    id="inn"
                    type="text"
                    class="form-control @error('inn') is-invalid @enderror"
                    wire:model.blur="inn"
                >

                @error('inn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="contact_person" class="form-label">
                    Контактное лицо <span class="text-danger">*</span>
                </label>

                <input
                    id="contact_person"
                    type="text"
                    class="form-control @error('contact_person') is-invalid @enderror"
                    wire:model.blur="contact_person"
                >

                @error('contact_person')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">
                    Телефон <span class="text-danger">*</span>
                </label>

                <input
                    id="phone"
                    type="text"
                    class="form-control @error('phone') is-invalid @enderror"
                    wire:model.blur="phone"
                >

                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    wire:model.blur="email"
                >

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="address" class="form-label">
                    Адрес
                </label>

                <input
                    id="address"
                    type="text"
                    class="form-control @error('address') is-invalid @enderror"
                    wire:model.blur="address"
                >

                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label for="note" class="form-label">
                    Примечание
                </label>

                <textarea
                    id="note"
                    rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    wire:model.blur="note"
                ></textarea>

                @error('note')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
            <a href="{{ route('counterparties.index') }}"  wire:navigate class="btn btn-secondary">
                Отмена
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i>
                Сохранить изминения
            </button>
        </div>
    </form>
</div>