<?php

namespace App\Filament\Widgets;

use App\Models\PengajuanAset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PengajuanDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Top Pengajuan Aset per Divisi';
    protected static ?int $sort = 3;
    protected static ?string $maxHeight = '300px';
    protected static bool $isDiscovered = false;

    public ?string $filter = 'this_year';

    protected function getFilters(): ?array
    {
        return [
            'this_month' => 'Bulan Ini',
            'this_year' => 'Tahun Ini',
            'all_time' => 'Semua Waktu',
        ];
    }

    protected function getData(): array
    {
        $query = PengajuanAset::select('requester_department', DB::raw('COUNT(*) as total_requests'))
            ->whereNotNull('requester_department')
            ->groupBy('requester_department')
            ->orderByDesc('total_requests')
            ->limit(10);

        if ($this->filter === 'this_month') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($this->filter === 'this_year') {
            $query->whereYear('created_at', now()->year);
        }

        $data = $query->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pengajuan',
                    'data' => $data->pluck('total_requests')->toArray(),
                    'backgroundColor' => '#f59e0b', // Amber/Orange
                ],
            ],
            'labels' => $data->pluck('requester_department')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
