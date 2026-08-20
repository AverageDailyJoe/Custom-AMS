<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AssetDepartmentChart;
use App\Filament\Widgets\PengajuanDepartmentChart;
use Filament\Pages\Page;

class LaporanAset extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Dashboard Laporan';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.laporan-aset';

    protected function getHeaderWidgets(): array
    {
        return [
            AssetDepartmentChart::class,
            PengajuanDepartmentChart::class,
        ];
    }
}
