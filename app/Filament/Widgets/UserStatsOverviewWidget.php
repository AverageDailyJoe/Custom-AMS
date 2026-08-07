<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class UserStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'user';
    }

    protected function getStats(): array
    {
        $userId = Auth::id();

        $totalUserTickets = Ticket::where('created_by', $userId)->count();

        $activeTickets = Ticket::where('created_by', $userId)
            ->whereIn('status', ['open', 'scheduled', 'in_progress', 'pending_sparepart', 'rescheduled'])
            ->count();

        $resolvedTickets = Ticket::where('created_by', $userId)
            ->whereIn('status', ['resolved', 'closed'])
            ->count();

        return [
            Stat::make('Total Tiket Saya', $totalUserTickets)
                ->description('Seluruh tiket pelaporan kendala Anda')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->color('info'),

            Stat::make('Dalam Proses IT', $activeTickets)
                ->description($activeTickets > 0 ? "{$activeTickets} Tiket sedang ditangani IT Staff" : 'Tidak ada tiket aktif')
                ->descriptionIcon('heroicon-m-clock')
                ->color($activeTickets > 0 ? 'warning' : 'gray'),

            Stat::make('Tiket Selesai (Resolved)', $resolvedTickets)
                ->description('Penanganan kendala selesai')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
