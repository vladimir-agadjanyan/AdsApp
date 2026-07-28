@props([
    'paginator',
])

@if ($paginator->hasPages())

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 gap-3">

    <div class="text-muted small">
        Показано
        <strong>{{ $paginator->firstItem() ?? 0 }}</strong>
        –
        <strong>{{ $paginator->lastItem() ?? 0 }}</strong>
        из
        <strong>{{ $paginator->total() }}</strong>
        записей
    </div>

    <nav>

        <ul class="pagination pagination-sm mb-0">

            {{-- Первая --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <button
                    class="page-link"
                    wire:click="gotoPage(1)"
                    @disabled($paginator->onFirstPage())
                >
                    «
                </button>
            </li>

            {{-- Назад --}}
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <button
                    class="page-link"
                    wire:click="previousPage"
                    @disabled($paginator->onFirstPage())
                >
                    ‹
                </button>
            </li>

            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();

                $start = max(1, $current - 2);
                $end = min($last, $current + 2);
            @endphp

            @if($start > 1)

                <li class="page-item">
                    <button
                        class="page-link"
                        wire:click="gotoPage(1)"
                    >
                        1
                    </button>
                </li>

                @if($start > 2)
                    <li class="page-item disabled">
                        <span class="page-link">…</span>
                    </li>
                @endif

            @endif

            @for($i = $start; $i <= $end; $i++)

                <li class="page-item {{ $current == $i ? 'active' : '' }}">
                    <button
                        class="page-link"
                        wire:click="gotoPage({{ $i }})"
                    >
                        {{ $i }}
                    </button>
                </li>

            @endfor

            @if($end < $last)

                @if($end < $last - 1)
                    <li class="page-item disabled">
                        <span class="page-link">…</span>
                    </li>
                @endif

                <li class="page-item">
                    <button
                        class="page-link"
                        wire:click="gotoPage({{ $last }})"
                    >
                        {{ $last }}
                    </button>
                </li>

            @endif

            {{-- Вперёд --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <button
                    class="page-link"
                    wire:click="nextPage"
                    @disabled(! $paginator->hasMorePages())
                >
                    ›
                </button>
            </li>

            {{-- Последняя --}}
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <button
                    class="page-link"
                    wire:click="gotoPage({{ $last }})"
                    @disabled(! $paginator->hasMorePages())
                >
                    »
                </button>
            </li>

        </ul>

    </nav>

</div>

@endif