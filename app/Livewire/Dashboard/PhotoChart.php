<?php

namespace App\Livewire\Dashboard;

use App\Models\PhotoReportStatus;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PhotoChart extends Component
{
    public function render(): View
    {
        $statuses = PhotoReportStatus::query()
            ->where('is_active', true)
            ->withCount('photoReports')
            ->orderBy('sort_order')
            ->get();

        $chartData = [
            'labels' => $statuses
                ->pluck('name')
                ->values()
                ->all(),

            'data' => $statuses
                ->pluck('photo_reports_count')
                ->values()
                ->all(),

            'colors' => $statuses
                ->map(fn (PhotoReportStatus $status) => match ($status->color) {
                    'success' => '#198754',
                    'warning' => '#ffc107',
                    'danger' => '#dc3545',
                    'primary' => '#0d6efd',
                    'info' => '#0dcaf0',
                    'secondary' => '#6c757d',
                    default => '#6c757d',
                })
                ->values()
                ->all(),
        ];

        return view('livewire.dashboard.photo-chart', [
            'chartData' => $chartData,
        ]);
    }
}
