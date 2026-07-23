<div class="card shadow-sm h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-file-earmark-text-fill me-2"></i>
            Истекающие договоры
        </h5>

        <span class="badge bg-danger">
            {{ count($contracts) }}
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
                @foreach($contracts as $contract)
                    <tr>
                        <td>№{{ $contract['number'] }}</td>
                        <td>{{ $contract['company'] }}</td>
                        <td>{{ $contract['end_date'] }}</td>
                        <td class="text-end fw-semibold">
                            <span @class([
                                'text-danger' => $contract['days_left'] <= 7,
                                'text-warning' => $contract['days_left'] > 7 && $contract['days_left'] <= 20,
                                'text-success' => $contract['days_left'] > 20,
                            ])>
                                {{ $contract['days_left'] }} дн.
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>