<?php

namespace App\Livewire\Counterparties;

use App\Models\Counterparty;
use App\Services\CounterpartyService;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;

class Index extends Component
{
    use WithPagination;

    public ?Counterparty $counterpartyToDelete = null;
    public bool $showDeleteModal = false;
    public string $search = '';

    public function confirmDelete(Counterparty $counterparty): void
    {
        $this->counterpartyToDelete = $counterparty;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->counterpartyToDelete = null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function delete(CounterpartyService $service): void
    {
        if (! $this->counterpartyToDelete) {
            return;
        }
    
        try {
            $service->delete($this->counterpartyToDelete);
        
            session()->flash(
                'success',
                'Контрагент успешно удален.'
            );
        } catch (RuntimeException $e) {
            session()->flash(
                'error',
                $e->getMessage()
            );
        }
    
        $this->cancelDelete();
    }

    public function render()
    {
        $counterparties = Counterparty::query()
            ->when($this->search, fn ($query) =>
                    $query->where('name', 'like', "%{$this->search}%")
                )
            ->latest()
            ->paginate(10);

        return view('livewire.counterparties.index', [
            'counterparties' => $counterparties,
        ]);
    }
}
