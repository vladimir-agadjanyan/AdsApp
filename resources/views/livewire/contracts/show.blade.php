<div class="contracts-show-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <h2 class="section-title mb-0">
                Договор № {{ $contract->number }}
            </h2>
            <span class="badge bg-{{ $contract->statusClass }}">
                {{ $contract->statusLabel }}
            </span>
        </div>

        <div class="d-flex gap-2">
            @can('update', $contract)
                <a href="{{ route('contracts.edit', $contract) }}" wire:navigate class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>
                    Редактировать
                </a>
            @endcan
            <a href="{{ route('contracts.index') }}" wire:navigate class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Назад
            </a>
        </div>
    </div>

    {{-- Основная информация --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Основная информация</strong>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <tbody>
                    <tr>
                        <th class="table-light" style="width: 260px;">
                            Контрагент
                        </th>
                        <td>
                            {{ $contract->counterparty->name }}
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Дата договора
                        </th>

                        <td>
                            {{ $contract->contract_date?->format('d.m.Y') ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Начало действия
                        </th>

                        <td>
                            {{ $contract->start_date?->format('d.m.Y') ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Окончание действия
                        </th>

                        <td>
                            {{ $contract->end_date?->format('d.m.Y') ?? '—' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Стоимость договора
                        </th>

                        <td class="fw-semibold">
                            {{ number_format((float) $contract->amount, 0, '.', ' ') }} сум
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Статус объекта
                        </th>

                        <td>
                            <span class="badge bg-{{ $contract->statusClass }}">
                                {{ $contract->statusLabel }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th class="table-light">
                            Примечание
                        </th>

                        <td>
                            {{ $contract->note ?: '—' }}
                        </td>
                    </tr>

                    {{-- Документы основного договора --}}
                    <tr>
                        <th class="table-light">
                            Документы договора
                        </th>

                        <td>
                            @if($contract->files->isEmpty())
                                <span class="text-muted">
                                    Документы не прикреплены.
                                </span>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    @foreach($contract->files as $file)
                                        <div class="border rounded px-3 py-2">
                                            <div class="fw-semibold">
                                                <button type="button" class="btn btn-link p-0 fw-semibold text-decoration-none" wire:click="openDocument({{ $file->id }})">
                                                    {{ $file->original_name }}
                                                </button>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                Загружен:
                                                {{ $file->created_at?->format('d.m.Y H:i') ?? '—' }}

                                                @if($file->file_size)
                                                    ·
                                                    {{ number_format(
                                                        $file->file_size / 1024,
                                                        1
                                                    ) }}
                                                    КБ
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    {{-- Дополнительные соглашения --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Дополнительные соглашения</strong>
        </div>

        <div class="card-body p-0">
            @if($contract->addendums->isEmpty())
                <div class="text-center text-muted py-5">
                    <i class="bi bi-file-earmark-text fs-1 d-block mb-3"></i>
                    Дополнительные соглашения отсутствуют.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>№ соглашения</th>
                                <th>Дата подписания</th>
                                <th>Действует до</th>
                                <th class="text-end">Изменение суммы</th>
                                <th>Примечание</th>
                                <th style="min-width: 300px;">Документы</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->addendums as $addendum)
                                <tr>
                                    {{-- Номер --}}
                                    <td class="fw-semibold">
                                        {{ $addendum->number }}
                                    </td>

                                    {{-- Дата подписания --}}
                                    <td>
                                        {{ $addendum->signed_at?->format('d.m.Y') ?? '—' }}
                                    </td>

                                    {{-- Дата окончания --}}
                                    <td>
                                        {{ $addendum->end_date?->format('d.m.Y') ?? '—' }}
                                    </td>

                                    {{-- Изменение суммы --}}
                                    <td class="text-end">
                                        @if((float) $addendum->amount_change > 0)
                                            <span class="text-success fw-semibold">
                                                + {{ number_format((float) $addendum->amount_change, 0, '.', ' ') }} сум
                                            </span>

                                        @elseif((float) $addendum->amount_change < 0)
                                            <span class="text-danger fw-semibold">
                                                {{ number_format((float) $addendum->amount_change, 0, '.', ' ') }}сум
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                0 сум
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Примечание --}}
                                    <td>
                                        {{ $addendum->note ?: '—' }}
                                    </td>

                                    {{-- Документы соглашения --}}
                                    <td>
                                        @if($addendum->files->isEmpty())
                                            <span class="text-muted"> — </span>
                                        @else
                                            <div class="d-flex flex-column gap-2">
                                                @foreach($addendum->files as $file)
                                                    <div class="border rounded px-2 py-2">
                                                        <div class="fw-semibold text-truncate">
                                                            <button type="button" class="btn btn-link p-0 fw-semibold text-decoration-none text-start" wire:click="openDocument({{ $file->id }})" title="{{ $file->original_name }}">
                                                                {{ $file->original_name }}
                                                            </button>
                                                        </div>

                                                        <div class="small text-muted mt-1">
                                                            {{ $file->created_at?->format('d.m.Y H:i') ?? '—' }}
                                                            @if($file->file_size)
                                                                · {{ number_format($file->file_size / 1024, 1) }} КБ
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Финансовая информация --}}
    <div class="card shadow-sm mb-4">

        <div class="card-header">
            <strong>Финансовая информация</strong>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <tbody>
                    <tr>
                        <th class="table-light" style="width: 260px;">
                            Стоимость договора
                        </th>
                        <td class="text-end fw-semibold">
                            {{ number_format((float) $contract->amount, 0, '.', ' ') }} сум
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">
                            Изменения по соглашениям
                        </th>
                        <td class="text-end">
                            @if($contract->addendumsAmount > 0)
                                <span class="text-success fw-semibold">
                                    + {{ number_format( $contract->addendumsAmount,  0, '.',  ' ' ) }} сум
                                </span>
                            @elseif($contract->addendumsAmount < 0)
                                <span class="text-danger fw-semibold">
                                    {{ number_format($contract->addendumsAmount, 0, '.', ' ') }} сум
                                </span>
                            @else
                                <span class="text-muted">
                                    0 сум
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light fs-5">
                            Итоговая стоимость
                        </th>
                        <td class="text-end">
                            <span class="fw-bold fs-5">
                                {{ number_format($contract->totalAmount, 0,'.', ' ') }} сум
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>