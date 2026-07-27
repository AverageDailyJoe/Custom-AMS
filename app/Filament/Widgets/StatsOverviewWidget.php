<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\DisposeAset;
use App\Models\PengajuanAset;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalAssets = Asset::count();
        $disposedAssets = Asset::where('status', 'disposed')->count();
        $inStockAssets = Asset::where('status', 'in_stock')->count();
        $checkedOutAssets = Asset::where('status', 'checked_out')->count();

        $ticketsToday = Ticket::whereDate('scheduled_date', now())->count();
        $scheduledTickets = Ticket::where('status', 'scheduled')->count();
        $inProgressTickets = Ticket::where('status', 'in_progress')->count();
        $resolvedTickets = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        $pendingPartTickets = Ticket::where('status', 'pending_sparepart')->count();
        $rescheduledTickets = Ticket::where('status', 'rescheduled')->count();

        return [
            Stat::make('Total Asset IT', $totalAssets)
                ->description("{$inStockAssets} Tersedia | {$checkedOutAssets} Digunakan | {$disposedAssets} Disposed")
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('success'),

            Stat::make('Jadwal Tiket Hari Ini', $ticketsToday)
                ->description("{$inProgressTickets} Sedang Dikerjakan")
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Tiket Terjadwal', $scheduledTickets)
                ->description("{$rescheduledTickets} Ubah Jadwal (Rescheduled)")
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Tiket Selesai (Resolved)', $resolvedTickets)
                ->description('Pengerjaan Selesai Ditangani IT')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Menunggu Part (PPB/LBS)', $pendingPartTickets)
                ->description('Dalam Pengadaan Sparepart')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('danger'),

            Stat::make('Total Disposal Aset', DisposeAset::count())
                ->description('Pengajuan Aset Rusak / Pemusnahan')
                ->descriptionIcon('heroicon-m-trash')
                ->color('danger'),
        ];
    }
}
