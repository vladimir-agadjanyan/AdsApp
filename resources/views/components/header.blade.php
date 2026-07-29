<header class="app-header">

    <div class="header-left">
        <h1 class="page-title">
            {{ $title ?? 'Панель управления' }}
        </h1>
    </div>

    <div class="header-right">

        <button class="header-icon-btn" type="button">
            <i class="bi bi-bell"></i>
        </button>

        <div class="d-flex align-items-center gap-3">

            <button class="user-button" type="button">

                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="user-data">
                    <span class="user-name">
                        {{ auth()->user()->name }}
                    </span>
                </div>

            </button>

            <livewire:auth.logout />


        </div>

    </div>

</header>