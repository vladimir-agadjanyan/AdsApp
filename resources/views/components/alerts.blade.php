@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session()->has($type))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition.opacity.duration.500ms
            class="alert alert-{{ $type === 'error' ? 'danger' : $type }}"
        >
            {{ session($type) }}
        </div>
    @endif
@endforeach