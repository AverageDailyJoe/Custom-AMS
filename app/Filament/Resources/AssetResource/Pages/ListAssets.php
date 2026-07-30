<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Location;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListAssets extends ListRecords
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf_rekap_aset')
                ->label('Cetak Rekap Aset (PDF)')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('period_type')
                        ->label('Periode Laporan')
                        ->options([
                            'all' => 'Semua Data Aset (Total Real-Time)',
                            'weekly' => 'Per Rentang Minggu / Tanggal Custom',
                            'monthly' => 'Per Bulan',
                            'yearly' => 'Per Tahun',
                        ])
                        ->default('all')
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

                    Forms\Components\Select::make('location_id')
                        ->label('Lokasi Audit Aset')
                        ->options(fn () => ['all' => 'Semua Lokasi (HQ, Cikarang, Surabaya)'] + Location::pluck('name', 'id')->toArray())
                        ->default('all')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('rekap-aset.pdf', $data);
                    return redirect()->away($url);
                }),

            Actions\Action::make('excel_rekap_aset')
                ->label('Ekspor Data Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('warning')
                ->form([
                    Forms\Components\Select::make('period_type')
                        ->label('Periode Laporan')
                        ->options([
                            'all' => 'Semua Data Aset (Total Real-Time)',
                            'weekly' => 'Per Rentang Minggu / Tanggal Custom',
                            'monthly' => 'Per Bulan',
                            'yearly' => 'Per Tahun',
                        ])
                        ->default('all')
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

                    Forms\Components\Select::make('location_id')
                        ->label('Lokasi Audit Aset')
                        ->options(fn () => ['all' => 'Semua Lokasi (HQ, Cikarang, Surabaya)'] + Location::pluck('name', 'id')->toArray())
                        ->default('all')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('rekap-aset.excel', $data);
                    return redirect()->away($url);
                }),

            Actions\CreateAction::make()->label('Tambah Aset Baru'),
        ];
    }
}
