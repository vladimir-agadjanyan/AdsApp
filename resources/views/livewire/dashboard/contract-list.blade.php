<div class="card shadow-sm h-100">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="bi bi-file-earmark-text-fill me-2"></i>
            Истекающие договоры
        </h5>

        <span class="badge bg-danger">
            {{ $contracts->count() }}
        </span>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead>
                <tr>
                    <th>Договор</th>
                    <th>Компания</th>
                    <th>Окончание</th>
                    <th class="text-end">Осталось</th>
                </tr>
            </thead>

            <tbody>

                @forelse($contracts as $contract)

                    @php
                        $daysLeft = today()->diffInDays(
                            $contract->end_date,
                            false
                        );
                    @endphp

                    <tr>

                        <td>
                            <a
                                href="{{ route('contracts.show', $contract) }}"
                                wire:navigate
                            >
                                №{{ $contract->number }}
                            </a>
                        </td>

                        <td>
                            {{ $contract->counterparty->name }}
                        </td>

                        <td>
                            {{ $contract->end_date->format('d.m.Y') }}
                        </td>

                        <td class="text-end fw-semibold">

                            <span @class([
                                'text-danger' => $daysLeft <= 7,
                                'text-warning' => $daysLeft > 7,
                            ])>
                                {{ $daysLeft }} дн.
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="4"
                            class="text-center py-5"
                        >
                            <i class="bi bi-check-circle fs-2 text-success d-block mb-2"></i>

                            <div class="fw-semibold">
                                Истекающих договоров нет
                            </div>

                            <div class="text-muted small mt-1">
                                В ближайшие 30 дней договоры не заканчиваются.
                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>