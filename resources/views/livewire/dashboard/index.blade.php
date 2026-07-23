<div>
    {{-- statistics --}}
    <section>
        <h2 class="section-title">
            Статистика
        </h2>
        <div class="row g-4">
            <x-card-stat
                icon="bi-file-earmark-text"
                value="154"
                title="Договоры"
                description="Всего договоров"
            />

            <x-card-stat
                icon="bi-geo-alt"
                value="742"
                title="Объекты"
                description="Рекламных объектов"
            />

            <x-card-stat
                icon="bi-camera"
                value="68"
                title="Фотоотчеты"
                description="Ожидают проверки"
            />

            <x-card-stat
                icon="bi-buildings"
                value="19"
                title="Контрагенты"
                description="Всего контрагентов"
            />

        </div>
    </section>
    {{-- Map --}}
    <section class="mt-5">

        <h2 class="section-title">
            Карта рекламных объектов
        </h2>

        <x-map />

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
                <x-dashboard-card>
                    @livewire('dashboard.photo-chart')
                </x-dashboard-card>
            </div>

        </div>

    </section>
</div>