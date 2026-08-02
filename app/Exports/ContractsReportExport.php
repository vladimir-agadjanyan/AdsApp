<?php

namespace App\Exports;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * @implements WithMapping<Contract>
 */
class ContractsReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithTitle
{
    public function __construct(
        private readonly ?int $regionId = null,
        private readonly ?int $counterpartyId = null,
        private readonly ?string $status = null,
        private readonly ?string $dateFrom = null,
        private readonly ?string $dateTo = null,
    ) {
    }

    /**
     * @return Builder<Contract>
     */
    public function query(): Builder
    {
        return Contract::query()
            ->with([
                'counterparty',
                'addendums',
            ])
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObjects.city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $this->regionId
                    )
                )
            )
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

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            '№ договора',
            'Контрагент',
            'Дата договора',
            'Начало',
            'Окончание',
            'Сумма',
            'Итого с доп. соглашениями',
            'Статус',
        ];
    }

    /**
     * @return array<int, string|float>
     */
    public function map($contract): array
    {
        return [
            $contract->number,
            $contract->counterparty->name,
            $contract->contract_date?->format('d.m.Y') ?? '—',
            $contract->start_date?->format('d.m.Y') ?? '—',
            $contract->end_date?->format('d.m.Y') ?? '—',
            (float) $contract->amount,
            $contract->total_amount,
            $contract->status_label,
        ];
    }

    public function title(): string
    {
        return 'Договоры';
    }
}