<?php

namespace App\Exports;

use App\Models\PhotoReport;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * @implements WithMapping<PhotoReport>
 */
class PhotoReportsReportExport implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private readonly ?int $regionId = null,
        private readonly ?int $cityId = null,
        private readonly ?int $counterpartyId = null,
        private readonly ?int $photoReportStatusId = null,
        private readonly ?string $dateFrom = null,
        private readonly ?string $dateTo = null,
    ) {}

    /**
     * @return Builder<PhotoReport>
     */
    public function query(): Builder
    {
        return PhotoReport::query()
            ->with([
                'advertisingObject.contract.counterparty',
                'advertisingObject.city.region',
                'photoReportStatus',
                'createdBy',
                'checkedBy',
            ])
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject.city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $this->regionId
                    )
                )
            )
            ->when(
                $this->cityId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject',
                    fn (Builder $object) => $object->where(
                        'city_id',
                        $this->cityId
                    )
                )
            )
            ->when(
                $this->counterpartyId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject.contract',
                    fn (Builder $contract) => $contract->where(
                        'counterparty_id',
                        $this->counterpartyId
                    )
                )
            )
            ->when(
                $this->photoReportStatusId,
                fn (Builder $query) => $query->where(
                    'photo_report_status_id',
                    $this->photoReportStatusId
                )
            )
            ->when(
                $this->dateFrom,
                fn (Builder $query) => $query->whereDate(
                    'created_at',
                    '>=',
                    $this->dateFrom
                )
            )
            ->when(
                $this->dateTo,
                fn (Builder $query) => $query->whereDate(
                    'created_at',
                    '<=',
                    $this->dateTo
                )
            )
            ->latest('created_at');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Объект',
            'Контрагент',
            'Регион',
            'Город',
            'Дата создания',
            'Статус',
            'Создал',
            'Проверил',
            'Дата проверки',
            'Комментарий',
            'Комментарий проверки',
        ];
    }

    /**
     * @return array<int, string>
     */
    public function map($photoReport): array
    {
        return [
            $photoReport->advertisingObject->name,

            $photoReport->advertisingObject
                ->contract
                ->counterparty
                ->name,

            $photoReport->advertisingObject
                ->city
                ->region
                ->name,

            $photoReport->advertisingObject
                ->city
                ->name,

            $photoReport->created_at?->format('d.m.Y H:i') ?? '—',
            $photoReport->photoReportStatus->name,
            optional($photoReport->createdBy)->name ?? '—',
            optional($photoReport->checkedBy)->name ?? '—',
            $photoReport->checked_at?->format('d.m.Y H:i') ?? '—',
            $photoReport->comment ?? '',
            $photoReport->review_comment ?? '',
        ];
    }

    public function title(): string
    {
        return 'Фотоотчеты';
    }
}
