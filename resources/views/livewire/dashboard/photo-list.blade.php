<div class="card shadow-sm h-100">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-camera-fill me-2"></i>
            Объекты без фотоотчета
        </h5>

        <span class="badge bg-danger">
            {{ count($objects) }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Объект</th>
                    <th>Город</th>
                    <th>Подрядчик</th>
                    <th class="text-end">Без фото</th>
                </tr>
            </thead>

            <tbody>
                @foreach($objects as $object)
                    <tr>
                        <td>{{ $object['name'] }}</td>

                        <td>{{ $object['city'] }}</td>

                        <td>{{ $object['contractor'] }}</td>

                        <td class="text-end fw-semibold">
                            <span @class([
                                'text-danger' => $object['days_without_photo'] > 7,
                                'text-warning' => $object['days_without_photo'] >= 4 && $object['days_without_photo'] <= 7,
                                'text-success' => $object['days_without_photo'] < 4,
                            ])>
                                {{ $object['days_without_photo'] }} дн.
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>