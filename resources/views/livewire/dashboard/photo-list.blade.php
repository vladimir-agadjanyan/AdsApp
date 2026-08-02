<div class="card shadow-sm h-100">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="bi bi-camera-fill me-2"></i>
            Объекты без фотоотчета
        </h5>

        <span class="badge bg-danger">
            {{ $objectsWithoutPhotoCount }}
        </span>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>Объект</th>
                    <th>Город</th>
                    <th>Контрагент</th>
                    <th class="text-end">Без фото</th>
                </tr>

            </thead>

            <tbody>

                @forelse($objects as $object)

                    <tr>

                        <td>

                            <a
                                href="{{ route('advertising-objects.show', $object['id']) }}"
                                wire:navigate
                                class="text-decoration-none"
                            >
                                {{ $object['name'] }}
                            </a>

                        </td>

                        <td>
                            {{ $object['city'] }}
                        </td>

                        <td>
                            {{ $object['counterparty'] }}
                        </td>

                        <td class="text-end fw-semibold">

                            <span @class([
                                'text-danger' =>
                                    $object['days_without_photo'] > 7,

                                'text-warning' =>
                                    $object['days_without_photo'] >= 4 &&
                                    $object['days_without_photo'] <= 7,

                                'text-success' =>
                                    $object['days_without_photo'] < 4,
                            ])>
                                {{ $object['days_without_photo'] }} дн.
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="text-center text-muted py-4"
                        >
                            <i class="bi bi-check-circle fs-4 d-block mb-2"></i>

                            Все объекты имеют фотоотчеты
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>