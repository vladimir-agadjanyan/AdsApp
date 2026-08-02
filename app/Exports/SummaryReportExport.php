<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SummaryReportExport implements WithMultipleSheets
{
    public function __construct(
        private readonly ?int $regionId = null,
        private readonly ?int $counterpartyId = null,
    ) {
    }

    /**
     * @return array<int, object>
     */
    public function sheets(): array
    {
        return [
            new SummarySheetExport(
                regionId: $this->regionId,
                counterpartyId: $this->counterpartyId,
            ),

            new ContractsReportExport(
                regionId: $this->regionId,
                counterpartyId: $this->counterpartyId,
            ),

            new AdvertisingObjectsReportExport(
                regionId: $this->regionId,
                counterpartyId: $this->counterpartyId,
            ),

            new PhotoReportsReportExport(
                regionId: $this->regionId,
                counterpartyId: $this->counterpartyId,
            ),
        ];
    }
}