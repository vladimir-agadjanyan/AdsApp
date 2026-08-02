@props([
    'open' => false,
    'title' => 'Фильтры',
])

<div x-data="{ showFilters: @js($open) }">

    <div class="mb-4">
        <button
            type="button"
            class="btn btn-filters d-inline-flex align-items-center"
            @click="showFilters = !showFilters"
        >
            <i
                class="bi me-2"
                :class="showFilters ? 'bi-chevron-up' : 'bi-chevron-down'"
            ></i>

            {{ $title }}
        </button>
    </div>

    <div
        x-show="showFilters"
        x-transition.duration.200ms
        x-cloak
        class="card border-0 bg-light mb-4"
    >
        <div class="card-body">
            {{ $slot }}
        </div>
    </div>

</div>