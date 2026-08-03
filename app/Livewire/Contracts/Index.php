<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Services\Contract\ContractService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $search = '';
    public ?int $counterpartyId = null;
    public ?string $status = null;
    public ?string $contractDateFrom = null;
    public ?string $contractDateTo = null;
    public string $paginationTheme = 'bootstrap';
    public ?Contract $contractToDelete = null;
    public bool $showDeleteModal = false;

    public function mount(): void
    {
        $this->authorize('viewAny', Contract::class);
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'counterpartyId',
            'status',
            'contractDateFrom',
            'contractDateTo',
        ]);

        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(Contract $contract): void
    {
        $this->authorize('delete', $contract);
        $this->contractToDelete = $contract;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->contractToDelete = null;
    }

    public function delete(ContractService $service): void 
    {
        if (! $this->contractToDelete) {
            return;
        }

        $this->authorize(
            'delete',
            $this->contractToDelete
        );

        try {
            $service->delete($this->contractToDelete);
            session()->flash('success', 'Договор успешно удален.');
        } catch (RuntimeException $e) {
            session()->flash('error', $e->getMessage());
        }

        $this->cancelDelete();
    }

    public function render()
    {
        $contracts = Contract::query()
            ->with('counterparty')

            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query
                        ->where(
                            'number',
                            'like',
                            "%{$this->search}%"
                        )
                        ->orWhereHas(
                            'counterparty',
                            function ($query) {
                                $query->where(
                                    'name',
                                    'like',
                                    "%{$this->search}%"
                                );
                            }
                        );
                });
            })

            ->when(
                $this->counterpartyId,
                function ($query) {
                    $query->where(
                        'counterparty_id',
                        $this->counterpartyId
                    );
                }
            )

            ->when(
                $this->contractDateFrom,
                function ($query) {
                    $query->whereDate(
                        'contract_date',
                        '>=',
                        $this->contractDateFrom
                    );
                }
            )

            ->when(
                $this->contractDateTo,
                function ($query) {
                    $query->whereDate(
                        'contract_date',
                        '<=',
                        $this->contractDateTo
                    );
                }
            )

            ->when($this->status, function ($query) {
                match ($this->status) {
                    'active' => $query->active(),
                    'expiring' => $query->expiring(),
                    'expired' => $query->expired(),
                    default => null,
                };
            })

            ->latest()
            ->paginate(15);

        return view('livewire.contracts.index', [
            'contracts' => $contracts,
            'counterparties' => Counterparty::query()
                ->orderBy('name')
                ->get(),
        ]);
    }
}