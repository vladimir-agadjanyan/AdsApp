<aside class="sidebar">

    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="logo">
            <i class="bi bi-broadcast-pin"></i>
            <span>AdsApp</span>
        </a>
    </div>

    <nav class="sidebar-nav">

        <a href="{{ route('dashboard') }}" wire:navigate class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
             <i class="bi bi-grid-1x2"></i>
             <span>Панель управления</span>
        </a>

        <a href="{{ route('contracts.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}" >
            <i class="bi bi-file-earmark-text"></i>
             Договоры
         </a>

        <a href="{{ route('counterparties.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('counterparties.*') ? 'active' : '' }}" >
            <i class="bi bi-buildings"></i>
            <span>Контрагенты</span>
        </a>

        <a href="{{ route('advertising-objects.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('advertising-objects.*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i>
            <span>Объекты</span>
        </a>

        <a href="{{ route('photo-reports.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('photo-reports.*') ? 'active' : '' }}" >
            <i class="bi bi-camera"></i>
            <span>Фотоотчеты</span>
        </a>

        <a href="{{ route('map.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('map.*') ? 'active' : '' }}">
            <i class="bi bi-map"></i>
            <span>Карта</span>
        </a>

        <a href="{{ route('reports.index') }}" wire:navigate class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i>
            <span>Отчеты</span>
        </a>

    </nav>

</aside>