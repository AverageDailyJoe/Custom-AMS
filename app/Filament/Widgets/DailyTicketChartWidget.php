<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DailyTicketChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Beban Kerja Layanan IT Harian (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subDays($i);
        });

        $labels = $days->map(fn ($date) => $date->format('d M'))->toArray();

        $totalEntered = $days->map(function ($date) {
            return Ticket::whereDate('created_at', $date->toDateString())->count();
        })->toArray();

        $totalResolved = $days->map(function ($date) {
            return Ticket::whereDate('resolved_at', $date->toDateString())->count();
        })->toArray();

        $totalScheduled = $days->map(function ($date) {
            return Ticket::whereDate('scheduled_date', $date->toDateString())->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tiket Masuk',
                    'data' => $totalEntered,
                    'backgroundColor' => '#10b981', // Emerald
                ],
                [
                    'label' => 'Tiket Selesai',
                    'data' => $totalResolved,
                    'backgroundColor' => '#3b82f6', // Blue
                ],
                [
                    'label' => 'Jadwal Pengerjaan',
                    'data' => $totalScheduled,
                    'backgroundColor' => '#f59e0b', // Yellow
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
