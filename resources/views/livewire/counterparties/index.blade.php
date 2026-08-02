<div class="counterparties-page">

    <x-alerts />

    {{-- Заголовок --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="section-title mb-0">
            Контрагенты
        </h2>

    </div>

    {{-- Поиск + кнопка --}}
    <div class="row align-items-center mb-3">

        {{-- Кнопка создания --}}
        @can('create', \App\Models\Counterparty::class)

            <div class="col-lg-auto mb-3 mb-lg-0">

                <a
                    href="{{ route('counterparties.create') }}"
                    wire:navigate
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg me-1"></i>
                    Новый контрагент
                </a>

            </div>

        @endcan

        {{-- Поиск --}}
        <div class="col-lg-5">

            <div class="input-group">

                <input
                    type="text"
                    class="form-control"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Поиск по контрагенту"
                >

                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>

            </div>

        </div>

    </div>

    {{-- Таблица --}}
    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>
                    <th>Название</th>
                    <th>ИНН</th>
                    <th>Телефон</th>
                    <th>Контактное лицо</th>
                    <th>Договоров</th>
                    <th width="150">Действия</th>
                </tr>

            </thead>

            <tbody>

                @forelse($counterparties as $counterparty)

                    <tr>

                        <td>
                            <a
                                href="{{ route('counterparties.show', $counterparty) }}"
                                wire:navigate
                            >
                                {{ $counterparty->name }}
                            </a>
                        </td>

                        <td>
                            {{ $counterparty->inn }}
                        </td>

                        <td>
                            {{ $counterparty->phone ?: '—' }}
                        </td>

                        <td>
                            {{ $counterparty->contact_person ?: '—' }}
                        </td>

                        <td>
                            -
                        </td>

                        <td>

                            <div class="btn-group">

                                @can('view', $counterparty)

                                    <a
                                        href="{{ route('counterparties.show', $counterparty) }}"
                                        wire:navigate
                                        class="btn btn-sm btn-outline-primary"
                                        title="Просмотр"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                @endcan

                                @can('update', $counterparty)

                                    <a
                                        href="{{ route('counterparties.edit', $counterparty) }}"
                                        wire:navigate
                                        class="btn btn-sm btn-outline-primary"
                                        title="Редактировать"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                @endcan

                                @can('delete', $counterparty)

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        wire:click="confirmDelete({{ $counterparty->id }})"
                                        title="Удалить"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </button>

                                @endcan

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5"
                        >

                            <i class="bi bi-buildings fs-1 text-secondary d-block mb-3"></i>

                            <h5 class="mb-2">
                                Контрагентов пока нет
                            </h5>

                            <p class="text-muted mb-0">
                                Контрагенты не найдены.
                            </p>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Модальное окно удаления --}}
    @if ($showDeleteModal)

        <div
            class="modal fade show d-block"
            tabindex="-1"
            style="background: rgba(0, 0, 0, .5);"
        >

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">
                            Удалить контрагента
                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            wire:click="cancelDelete"
                        ></button>

                    </div>

                    <div class="modal-body">

                        <p class="mb-2">
                            Вы действительно хотите удалить контрагента:
                        </p>

                        <p class="fw-semibold mb-3">
                            {{ $counterpartyToDelete?->name }}
                        </p>

                        <div class="alert alert-warning mb-0">
                            Действие необратимо.
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            wire:click="cancelDelete"
                        >
                            Отмена
                        </button>

                        <button
                            type="button"
                            class="btn btn-danger"
                            wire:click="delete"
                            wire:loading.attr="disabled"
                            wire:target="delete"
                        >

                            <span
                                wire:loading.remove
                                wire:target="delete"
                            >
                                <i class="bi bi-trash me-1"></i>
                                Удалить
                            </span>

                            <span
                                wire:loading
                                wire:target="delete"
                            >
                                Удаление...
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif

    {{-- Пагинация --}}
    <x-pagination :paginator="$counterparties" />

</div>