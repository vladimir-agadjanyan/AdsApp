<div>
    {{-- statistics --}}
    <section>
        <h2 class="section-title">
            Статистика
        </h2>

        <div class="row g-4">

            <x-card-stat
                icon="bi-file-earmark-text"
                :value="$contractsCount"
                title="Договоры"
                description="Всего договоров"
            />

            <x-card-stat
                icon="bi-geo-alt"
                :value="$advertisingObjectsCount"
                title="Объекты"
                description="Рекламных объектов"
            />

            <x-card-stat
                icon="bi-camera"
                :value="$photoReportsCount"
                title="Фотоотчеты"
                description="Ожидают проверки"
            />

            <x-card-stat
                icon="bi-buildings"
                :value="$counterpartiesCount"
                title="Контрагенты"
                description="Всего контрагентов"
            />

        </div>
    </section>
    {{-- Map --}}
    <section class="mt-5">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h2 class="section-title mb-0">
                Карта рекламных объектов
            </h2>

            <a href="{{ route('map.index') }}" wire:navigate class="btn btn-sm btn-outline-primary">
                Открыть карту
                <i class="bi bi-arrow-right ms-1"></i>
            </a>

        </div>

        <x-map :advertising-objects="$advertisingObjects" />

    </section>
    {{-- Contracts --}}

    <section class="mt-5">

        <div class="row g-4">

            <div class="col-lg-8">
                <x-dashboard-card>
                    @livewire('dashboard.contract-list')
                </x-dashboard-card>
            </div>

            <div class="col-lg-4 ali">
                <x-dashboard-card>
                    @livewire('dashboard.contract-chart')
                </x-dashboard-card>
            </div>

        </div>

    </section>
    {{-- Photo-Reports --}}
    <section class="mt-5">

        <div class="row g-4">

            <div class="col-lg-8">
                <x-dashboard-card>
                    @livewire('dashboard.photo-list')
                </x-dashboard-card>
            </div>

            <div class="col-lg-4">
                <x-dashboard-card >
                    @livewire('dashboard.photo-chart')
                </x-dashboard-card>
            </div>

        </div>

    </section>
</div>