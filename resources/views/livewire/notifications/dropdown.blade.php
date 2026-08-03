<div class="dropdown">

    <button
        class="header-icon-btn position-relative"
        type="button"
        wire:click="toggle"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
    >
        <i class="bi bi-bell fs-5"></i>

        @if($unreadCount > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle
                       badge rounded-pill bg-danger"
            >
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if($isOpen)
        <div
            class="dropdown-menu dropdown-menu-end show shadow border-0 p-0"
            style="width: 360px;"
        >
            <div
                class="d-flex justify-content-between
                       align-items-center px-3 py-3 border-bottom"
            >
                <strong>Уведомления</strong>

                @if($unreadCount > 0)
                    <button
                        type="button"
                        class="btn btn-link btn-sm text-decoration-none p-0"
                        wire:click="markAllAsRead"
                    >
                        Прочитать все
                    </button>
                @endif
            </div>

            <div style="max-height: 400px; overflow-y: auto;">

                @forelse($notifications as $notification)

                    <button
                        type="button"
                        wire:key="notification-{{ $notification->id }}"
                        wire:click="markAsRead({{ $notification->id }})"
                        @class([
                            'dropdown-item text-wrap border-bottom py-3',
                            'bg-light' => ! $notification->is_read,
                        ])
                    >
                        <div class="d-flex gap-2">

                            <i
                                @class([
                                    'bi mt-1',
                                    'bi-bell-fill text-primary' => ! $notification->is_read,
                                    'bi-bell text-muted' => $notification->is_read,
                                ])
                            ></i>

                            <div class="flex-grow-1">

                                <div class="fw-semibold">
                                    {{ $notification->title }}
                                </div>

                                <div class="small text-muted mt-1">
                                    {{ $notification->message }}
                                </div>

                                <div class="small text-muted mt-2">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </div>

                            </div>

                        </div>
                    </button>

                @empty

                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bell fs-4 d-block mb-2"></i>
                        Нет уведомлений
                    </div>

                @endforelse

            </div>

        </div>
    @endif

</div>