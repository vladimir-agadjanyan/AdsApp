<?php

namespace App\Livewire\Contracts;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\ContractAddendum;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;


class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $counterpartyId = null;
    public ?string $status = null;
    public ?string $contractDateFrom = null;
    public ?string $contractDateTo = null;
    public string $paginationTheme = 'bootstrap';

    public function create(): void
    {
        //
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
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $contract = Contract::with(['addendums', 'files'])->findOrFail($id);

        $number = $contract->number;

        $contract->delete();

        session()->flash(
            'success',
            "Договор №{$number} успешно удален."
        );
    }

    public function render()
    {
        $contracts = Contract::query()
        ->with('counterparty')

        ->when($this->search, function ($query) {
            $query->where(function ($query) {
                $query->where('number', 'like', "%{$this->search}%")
                    ->orWhereHas('counterparty', function ($query) {
                        $query->where('name', 'like', "%{$this->search}%");
                    });
            });
        })

        ->when($this->counterpartyId, function ($query) {
            $query->where('counterparty_id', $this->counterpartyId);
        })

        ->when($this->contractDateFrom, function ($query) {
            $query->whereDate('contract_date', '>=', $this->contractDateFrom);
        })

        ->when($this->contractDateTo, function ($query) {
            $query->whereDate('contract_date', '<=', $this->contractDateTo);
        })

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
            'counterparties' => Counterparty::orderBy('name')->get(),
        ]);
    }
}
