<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Загрузить документы</h5>
        </div>

        <div class="card-body">

            <form wire:submit="save" class="row g-3">

                <div class="col-md-9">
                    <input
                        type="file"
                        wire:model="document"
                        class="form-control @error('document') is-invalid @enderror"
                    >

                    @error('document')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div
                        wire:loading
                        wire:target="document"
                        class="form-text"
                    >
                        Загрузка файла...
                    </div>
                </div>

                <div class="col-md-3">
                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                        wire:loading.attr="disabled"
                    >
                        Загрузить
                    </button>
                </div>

            </form>

        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header">
            <h5 class="mb-0">Загруженные документы</h5>
        </div>

        <div class="card-body p-0">

            @if ($contract->files->isEmpty())

                <div class="text-center text-muted py-5">
                    Документы пока не загружены.
                </div>

            @else

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">
                        <tr>
                            <th>Файл</th>
                            <th width="120">Размер</th>
                            <th width="180">Загрузил</th>
                            <th width="180">Дата</th>
                            <th width="120" class="text-center">
                                Действия
                            </th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($contract->files as $file)

                            <tr>

                                <td>

                                    <i class="bi bi-file-earmark-text me-2"></i>

                                    {{ $file->original_name }}

                                </td>

                                <td>

                                    {{ number_format($file->file_size / 1024, 1) }} КБ

                                </td>

                                <td>

                                    {{ $file->uploader?->name ?? '—' }}

                                </td>

                                <td>

                                    {{ $file->created_at->format('d.m.Y H:i') }}

                                </td>

                                <td class="text-center">

                                    <button
                                        class="btn btn-sm btn-outline-primary"
                                        wire:click="download({{ $file->id }})"
                                        title="Скачать"
                                    >
                                        <i class="bi bi-download"></i>
                                    </button>

                                    <button
                                        class="btn btn-sm btn-outline-danger ms-1"
                                        wire:click="delete({{ $file->id }})"
                                        wire:confirm="Удалить файл?"
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

            @endif

        </div>
    </div>

</div>