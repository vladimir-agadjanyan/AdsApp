<div class="reports-page">

    <div class="mb-4">
        <h2 class="section-title mb-1">
            Отчеты
        </h2>

        <p class="text-muted mb-0">
            Формирование и анализ отчетов по данным системы
        </p>
    </div>

    <div class="row g-4">

        {{-- Договоры --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                    </div>

                    <h5 class="card-title">
                        Договоры
                    </h5>

                    <p class="text-muted">
                        Отчет по договорам, срокам действия и контрагентам.
                    </p>

                    <a href="{{ route('reports.contracts') }}"  wire:navigate class="btn btn-outline-primary">
                        Сформировать
                    </a>

                </div>
            </div>
        </div>

        {{-- Рекламные объекты --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <i class="bi bi-geo-alt fs-2 text-primary"></i>
                    </div>

                    <h5 class="card-title">
                        Рекламные объекты
                    </h5>

                    <p class="text-muted">
                        Отчет по регионам, городам, типам рекламы и статусам.
                    </p>

                    <a href="{{ route('reports.advertising-objects') }}" wire:navigate class="btn btn-outline-primary">
                        Сформировать
                    </a>

                </div>
            </div>
        </div>

        {{-- Фотоотчеты --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <i class="bi bi-camera fs-2 text-primary"></i>
                    </div>

                    <h5 class="card-title">
                        Фотоотчеты
                    </h5>

                    <p class="text-muted">
                        Отчет по фотоотчетам и результатам проверки.
                    </p>

                    <a href="{{ route('reports.photo-reports') }}" wire:navigate class="btn btn-outline-primary">
                        Сформировать
                    </a>

                </div>
            </div>
        </div>

        {{-- Сводный отчет --}}
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">

                    <div class="mb-3">
                        <i class="bi bi-bar-chart fs-2 text-primary"></i>
                    </div>

                    <h5 class="card-title">
                        Сводный отчет
                    </h5>

                    <p class="text-muted">
                        Общая статистика по основным показателям системы.
                    </p>

                    <a  href="{{ route('reports.summary') }}" wire:navigate class="btn btn-outline-primary">
                        Сформировать
                    </a>

                </div>
            </div>
        </div>

    </div>

</div>