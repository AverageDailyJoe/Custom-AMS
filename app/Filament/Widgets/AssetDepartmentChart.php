<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AssetDepartmentChart extends ChartWidget
{
    protected static ?string $heading = 'Total Nilai Aset per Divisi (Rp)';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '300px';
    protected static bool $isDiscovered = false;

    protected function getData(): array
    {
        // Ambil 10 divisi dengan total aset termahal
        $data = Asset::select('department', DB::raw('SUM(purchase_cost) as total_cost'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->orderByDesc('total_cost')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Nilai Aset (Rp)',
                    'data' => $data->pluck('total_cost')->map(fn ($cost) => (float) $cost)->toArray(),
                    'backgroundColor' => '#3b82f6', // Biru
                ],
            ],
            'labels' => $data->pluck('department')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
