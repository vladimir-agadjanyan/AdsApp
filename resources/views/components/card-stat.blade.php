<div class="col-12 col-sm-6 col-xl-3">
    <div class="card-stat">

        <div class="card-stat__icon">
            <i class="bi {{ $icon }}"></i>
        </div>

        <div class="card-stat__content">

            <div class="card-stat__value">
                {{ $value }}
            </div>

            <div class="card-stat__title">
                {{ $title }}
            </div>

            @isset($description)
                <div class="card-stat__description">
                    {{ $description }}
                </div>
            @endisset

        </div>

    </div>
</div>