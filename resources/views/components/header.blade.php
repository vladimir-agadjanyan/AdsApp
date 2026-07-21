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

        <button class="user-button" type="button">

            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'Г', 0, 1)) }}
            </div>

            <div class="user-data">
                <span class="user-name">
                    {{ auth()->user()->name ?? 'Гость' }}
                </span>
            </div>

            <i class="bi bi-chevron-down"></i>

        </button>

    </div>

</header>