<div class="row justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="col-md-5 col-lg-4">

        <div class="card shadow border-0">

            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <h2 class="fw-bold">AdsApp</h2>
                    <p class="text-muted mb-0">
                        Вход в систему
                    </p>
                </div>

                <form wire:submit="login">

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            wire:model.live="email"
                            class="form-control @error('email') is-invalid @enderror"
                            autocomplete="email"
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Пароль
                        </label>

                        <input
                            id="password"
                            type="password"
                            wire:model.live="password"
                            class="form-control @error('password') is-invalid @enderror"
                            autocomplete="current-password"
                        >

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input
                            id="remember"
                            type="checkbox"
                            class="form-check-input"
                            wire:model="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Запомнить меня
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Войти
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>
