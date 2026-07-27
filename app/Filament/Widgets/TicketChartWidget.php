<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TicketChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Grafik Analisis Tiket Layanan IT (Per Bulan)';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function ($i) {
            return Carbon::now()->subMonths($i);
        });

        $labels = $months->map(fn ($date) => $date->format('M Y'))->toArray();

        $totalEntered = $months->map(function ($date) {
            return Ticket::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        })->toArray();

        $totalScheduled = $months->map(function ($date) {
            return Ticket::whereYear('scheduled_date', $date->year)
                ->whereMonth('scheduled_date', $date->month)
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->count();
        })->toArray();

        $totalResolved = $months->map(function ($date) {
            return Ticket::whereYear('scheduled_date', $date->year)
                ->whereMonth('scheduled_date', $date->month)
                ->whereIn('status', ['resolved', 'closed'])
                ->count();
        })->toArray();

        $totalRescheduled = $months->map(function ($date) {
            return Ticket::whereYear('scheduled_date', $date->year)
                ->whereMonth('scheduled_date', $date->month)
                ->where('status', 'rescheduled')
                ->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Tiket Masuk',
                    'data' => $totalEntered,
                    'borderColor' => '#10b981', // Emerald
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                ],
                [
                    'label' => 'Tiket Selesai (Resolved)',
                    'data' => $totalResolved,
                    'borderColor' => '#3b82f6', // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => 'Tiket Terjadwal (Scheduled)',
                    'data' => $totalScheduled,
                    'borderColor' => '#f59e0b', // Yellow
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                ],
                [
                    'label' => 'Jadwal Diubah (Rescheduled)',
                    'data' => $totalRescheduled,
                    'borderColor' => '#ef4444', // Red
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
