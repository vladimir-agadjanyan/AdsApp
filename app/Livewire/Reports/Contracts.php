<?php

namespace App\Livewire\Reports;

use App\Models\Contract;
use App\Models\Counterparty;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

use App\Exports\ContractsReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Contracts extends Component
{
    public ?int $counterpartyId = null;

    public ?string $status = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function resetFilters(): void
    {
        $this->reset([
            'counterpartyId',
            'status',
            'dateFrom',
            'dateTo',
        ]);
    }

    /**
     * @return Builder<Contract>
     */
    private function contractsQuery(): Builder
    {
        return Contract::query()
            ->with([
                'counterparty',
                'addendums',
            ])
            ->when(
                $this->counterpartyId,
                fn (Builder $query) => $query->where(
                    'counterparty_id',
                    $this->counterpartyId
                )
            )
            ->when(
                $this->status === 'active',
                fn (Builder $query) => $query->active()
            )
            ->when(
                $this->status === 'expiring',
                fn (Builder $query) => $query->expiring()
            )
            ->when(
                $this->status === 'expired',
                fn (Builder $query) => $query->expired()
            )
            ->when(
                $this->dateFrom,
                fn (Builder $query) => $query->whereDate(
                    'end_date',
                    '>=',
                    $this->dateFrom
                )
            )
            ->when(
                $this->dateTo,
                fn (Builder $query) => $query->whereDate(
                    'end_date',
                    '<=',
                    $this->dateTo
                )
            )
            ->orderBy('end_date');
    }

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(
            new ContractsReportExport(
                counterpartyId: $this->counterpartyId,
                status: $this->status,
                dateFrom: $this->dateFrom,
                dateTo: $this->dateTo,
            ),
            'contracts-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function render(): View
    {
        return view('livewire.reports.contracts', [
            'contracts' => $this->contractsQuery()->get(),

            'counterparties' => Counterparty::query()
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app');
    }
}