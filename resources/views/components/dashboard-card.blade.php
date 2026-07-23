@props([
    'title' => '',
    'link' => null,
    'linkText' => 'Все',
])

<div {{ $attributes->merge(['class' => 'dashboard-card']) }}>

    <div class="dashboard-card__header">

        <h3 class="dashboard-card__title">
            {{ $title }}
        </h3>

        @if($link)
            <a href="{{ $link }}" class="dashboard-card__link">
                {{ $linkText }}
                <i class="bi bi-arrow-right-short"></i>
            </a>
        @endif

    </div>

    <div class="dashboard-card__body">
        {{ $slot }}
    </div>

</div>