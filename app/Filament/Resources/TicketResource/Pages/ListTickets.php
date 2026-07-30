<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf_rekap_tiket')
                ->label('Cetak Laporan Maintenance (PDF)')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('period_type')
                        ->label('Periode Laporan Tiket')
                        ->options([
                            'all' => 'Semua Tiket (Total Histori)',
                            'weekly' => 'Per Rentang Minggu / Tanggal Custom',
                            'monthly' => 'Per Bulan',
                            'yearly' => 'Per Tahun',
                        ])
                        ->default('monthly')
                        ->live()
                        ->required(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('Dari Tanggal')
                            ->default(now()->startOfWeek())
                            ->visible(fn (Forms\Get $get) => $get('period_type') === 'weekly'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('Sampai Tanggal')
                            ->default(now()->endOfWeek())
                            ->visible(fn (Forms\Get $get) => $get('period_type') === 'weekly'),
                    ]),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('month')
                            ->label('Bulan')
                            ->options([
                                '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                                '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
                                '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                            ])
                            ->default((string)date('n'))
                            ->visible(fn (Forms\Get $get) => $get('period_type') === 'monthly'),

                        Forms\Components\Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(range(date('Y'), date('Y') - 10), range(date('Y'), date('Y') - 10)))
                            ->default((string)date('Y'))
                            ->visible(fn (Forms\Get $get) => in_array($get('period_type'), ['monthly', 'yearly'])),
                    ]),

                    Forms\Components\Select::make('status')
                        ->label('Filter Status Tiket')
                        ->options([
                            'all' => 'Semua Status Tiket',
                            'open' => 'Open (Tiket Baru)',
                            'scheduled' => 'Scheduled (Terjadwal)',
                            'in_progress' => 'In Progress (Sedang Dikerjakan)',
                            'pending_sparepart' => 'Pending Sparepart (PPB)',
                            'rescheduled' => 'Rescheduled (Jadwal Diubah)',
                            'resolved' => 'Resolved (Selesai IT)',
                            'closed' => 'Closed (Selesai Total)',
                        ])
                        ->default('all')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('rekap-tiket.pdf', $data);
                    return redirect()->away($url);
                }),

            Actions\CreateAction::make()->label('Buat Tiket Layanan Baru'),
        ];
    }
}
