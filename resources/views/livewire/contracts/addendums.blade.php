<div>

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-1">
                Дополнительные соглашения
            </h5>

            <p class="text-muted mb-0">
                Дополнительные соглашения к договору
            </p>
        </div>

        @can('update', $contract)
            <button
                type="button"
                class="btn btn-primary btn-sm"
                wire:click="create"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Добавить соглашение
            </button>
        @endcan

    </div>


    {{-- Форма создания / редактирования --}}
    @if($showForm)

        <div class="card border-primary mb-4">

            <div class="card-header">
                <strong>
                    @if($editingAddendumId)
                        Редактирование дополнительного соглашения
                    @else
                        Новое дополнительное соглашение
                    @endif
                </strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Номер --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            № соглашения
                        </label>

                        <input
                            type="text"
                            class="form-control @error('number') is-invalid @enderror"
                            wire:model.blur="number"
                        >

                        @error('number')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Изменение стоимости --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Изменение стоимости
                        </label>

                        <div class="input-group">

                            <input
                                type="number"
                                step="1"
                                class="form-control @error('amount_change') is-invalid @enderror"
                                wire:model.blur="amount_change"
                            >

                            <span class="input-group-text">
                                сум
                            </span>

                            @error('amount_change')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                        <div class="form-text">
                            Можно указать положительное или отрицательное значение.
                        </div>

                    </div>


                    {{-- Дата подписания --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Дата подписания
                        </label>

                        <input
                            type="date"
                            class="form-control @error('signed_at') is-invalid @enderror"
                            wire:model.blur="signed_at"
                        >

                        @error('signed_at')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Дата окончания --}}
                    <div class="col-md-6">

                        <label class="form-label">
                            Дата окончания
                        </label>

                        <input
                            type="date"
                            class="form-control @error('end_date') is-invalid @enderror"
                            wire:model.blur="end_date"
                        >

                        @error('end_date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Примечание --}}
                    <div class="col-12">

                        <label class="form-label">
                            Примечание
                        </label>

                        <textarea
                            rows="3"
                            class="form-control @error('note') is-invalid @enderror"
                            wire:model.blur="note"
                        ></textarea>

                        @error('note')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Документ --}}
                    <div class="col-12">

                        <label class="form-label">
                            Документ соглашения
                        </label>

                        <input
                            type="file"
                            class="form-control @error('document') is-invalid @enderror"
                            wire:model="document"
                        >

                        @error('document')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="form-text">
                            PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG.
                            Максимальный размер — 10 МБ.
                        </div>

                        <div
                            wire:loading
                            wire:target="document"
                            class="form-text"
                        >
                            <span
                                class="spinner-border spinner-border-sm me-1"
                                role="status"
                            ></span>

                            Загрузка документа...
                        </div>

                    </div>


                    {{-- Уже прикреплённые документы при редактировании --}}
                    @if($editingAddendumId)

                        @php
                            $editingAddendum = $contract
                                ->addendums
                                ->firstWhere('id', $editingAddendumId);
                        @endphp

                        @if($editingAddendum && $editingAddendum->files->isNotEmpty())

                            <div class="col-12">

                                <label class="form-label">
                                    Прикреплённые документы
                                </label>

                                <div class="table-responsive border rounded">

                                    <table class="table table-sm table-hover align-middle mb-0">

                                        <thead class="table-light">
                                            <tr>
                                                <th>Файл</th>
                                                <th width="120">
                                                    Размер
                                                </th>
                                                <th width="180">
                                                    Загрузил
                                                </th>
                                                <th width="160">
                                                    Дата
                                                </th>
                                                <th
                                                    width="120"
                                                    class="text-end"
                                                >
                                                    Действия
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @foreach($editingAddendum->files as $file)

                                                <tr wire:key="editing-addendum-file-{{ $file->id }}">

                                                    <td>

                                                        <i class="bi bi-file-earmark-text me-2"></i>

                                                        {{ $file->original_name }}

                                                    </td>

                                                    <td>
                                                        {{ number_format(
                                                            $file->file_size / 1024,
                                                            1
                                                        ) }}
                                                        КБ
                                                    </td>

                                                    <td>
                                                        {{ $file->uploader?->name ?? '—' }}
                                                    </td>

                                                    <td>
                                                        {{ $file->created_at?->format('d.m.Y H:i') ?? '—' }}
                                                    </td>

                                                    <td class="text-end">

                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary"
                                                            wire:click="downloadDocument({{ $file->id }})"
                                                            title="Скачать"
                                                        >
                                                            <i class="bi bi-download"></i>
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            wire:click="deleteDocument({{ $file->id }})"
                                                            wire:confirm="Удалить документ {{ $file->original_name }}?"
                                                            title="Удалить"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        @endif

                    @endif

                </div>

            </div>


            {{-- Кнопки формы --}}
            <div class="card-footer d-flex justify-content-end gap-2">

                <button
                    type="button"
                    class="btn btn-outline-secondary"
                    wire:click="cancel"
                    wire:loading.attr="disabled"
                    wire:target="save,document"
                >
                    Отмена
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    wire:click="save"
                    wire:loading.attr="disabled"
                    wire:target="save,document"
                >

                    <span
                        wire:loading.remove
                        wire:target="save"
                    >

                        @if($editingAddendumId)

                            <i class="bi bi-check-lg me-1"></i>
                            Сохранить изменения

                        @else

                            <i class="bi bi-plus-lg me-1"></i>
                            Добавить соглашение

                        @endif

                    </span>

                    <span
                        wire:loading
                        wire:target="save"
                    >

                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                        ></span>

                        Сохранение...

                    </span>

                </button>

            </div>

        </div>

    @endif


    {{-- Список соглашений --}}
    @if($contract->addendums->isEmpty())

        <div class="alert alert-light border text-center mb-0">

            <i class="bi bi-file-earmark-text fs-2 d-block mb-2"></i>

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
                        <th>Изменение стоимости</th>
                        <th>Документы</th>
                        <th class="text-end">
                            Действия
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($contract->addendums as $addendum)

                        <tr wire:key="addendum-{{ $addendum->id }}">

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


                            {{-- Изменение стоимости --}}
                            <td>

                                @if($addendum->amount_change > 0)

                                    <span class="text-success fw-semibold">
                                        {{ $addendum->formatted_amount_change }} сум
                                    </span>

                                @elseif($addendum->amount_change < 0)

                                    <span class="text-danger fw-semibold">
                                        {{ $addendum->formatted_amount_change }} сум
                                    </span>

                                @else

                                    <span class="text-muted">
                                        0 сум
                                    </span>

                                @endif

                            </td>


                            {{-- Документы --}}
                            <td>

                                @if($addendum->files->isEmpty())

                                    <span class="text-muted">
                                        —
                                    </span>

                                @else

                                    <span
                                        class="badge text-bg-secondary"
                                        title="Количество документов"
                                    >
                                        <i class="bi bi-paperclip me-1"></i>
                                        {{ $addendum->files->count() }}
                                    </span>

                                @endif

                            </td>


                            {{-- Действия --}}
                            <td class="text-end">

                                @can('update', $contract)

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        wire:click="edit({{ $addendum->id }})"
                                        title="Редактировать"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $addendum->id }})"
                                        wire:confirm="Удалить дополнительное соглашение №{{ $addendum->number }}?"
                                        title="Удалить"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                @endcan

                            </td>

                        </tr>


                        {{-- Дополнительная информация --}}
                        @if($addendum->note || $addendum->files->isNotEmpty())

                            <tr wire:key="addendum-details-{{ $addendum->id }}">

                                <td colspan="6">

                                    {{-- Примечание --}}
                                    @if($addendum->note)

                                        <div class="small text-muted mb-2">

                                            <strong>
                                                Примечание:
                                            </strong>

                                            {{ $addendum->note }}

                                        </div>

                                    @endif


                                    {{-- Документы соглашения --}}
                                    @if($addendum->files->isNotEmpty())

                                        <div class="small">

                                            <strong class="d-block mb-2">
                                                Документы:
                                            </strong>

                                            <div class="d-flex flex-column gap-2">

                                                @foreach($addendum->files as $file)

                                                    <div
                                                        class="d-flex align-items-center justify-content-between border rounded px-3 py-2"
                                                        wire:key="addendum-file-{{ $file->id }}"
                                                    >

                                                        <div class="text-truncate me-3">

                                                            <i class="bi bi-paperclip me-1"></i>

                                                            {{ $file->original_name }}

                                                            <span class="text-muted ms-2">

                                                                {{ number_format(
                                                                    $file->file_size / 1024,
                                                                    1
                                                                ) }}
                                                                КБ

                                                            </span>

                                                        </div>


                                                        <div class="d-flex gap-1 flex-shrink-0">

                                                            <button
                                                                type="button"
                                                                class="btn btn-sm btn-outline-primary"
                                                                wire:click="downloadDocument({{ $file->id }})"
                                                                title="Скачать"
                                                            >
                                                                <i class="bi bi-download"></i>
                                                            </button>

                                                            @can('update', $contract)

                                                                <button
                                                                    type="button"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    wire:click="deleteDocument({{ $file->id }})"
                                                                    wire:confirm="Удалить документ {{ $file->original_name }}?"
                                                                    title="Удалить"
                                                                >
                                                                    <i class="bi bi-trash"></i>
                                                                </button>

                                                            @endcan

                                                        </div>

                                                    </div>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @endif

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>