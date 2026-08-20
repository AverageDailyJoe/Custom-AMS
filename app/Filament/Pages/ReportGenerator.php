<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;

class ReportGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-down';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $title = 'Generate Laporan Ekspor';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.report-generator';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Kriteria Laporan')
                    ->description('Pilih jenis laporan yang ingin diekspor ke format CSV (bisa dibuka di Excel).')
                    ->schema([
                        Select::make('report_type')
                            ->label('Jenis Laporan')
                            ->options([
                                'asset_per_dept' => 'Rekap Aset per Departemen',
                                'asset_overall' => 'Rekap Aset Keseluruhan (Total & Status)',
                                'pengajuan_per_dept' => 'Rekap Pengajuan per Departemen & User',
                                'pengajuan_recap' => 'Rekap Riwayat Pengajuan (Berdasarkan Rentang Waktu)',
                                'berita_acara_recap' => 'Rekap Riwayat Berita Acara',
                                'disposal_recap' => 'Rekap Riwayat Disposal Aset',
                            ])
                            ->required()
                            ->reactive(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->hidden(fn (\Filament\Forms\Get $get) => !in_array($get('report_type'), ['pengajuan_recap', 'berita_acara_recap', 'disposal_recap'])),
                        DatePicker::make('end_date')
                            ->label('Tanggal Sampai')
                            ->hidden(fn (\Filament\Forms\Get $get) => !in_array($get('report_type'), ['pengajuan_recap', 'berita_acara_recap', 'disposal_recap'])),
                    ])->columns(2)
            ])
            ->statePath('data');
    }
    
    public function downloadReport()
    {
        $data = $this->form->getState();
        $url = route('laporan.download', [
            'type' => $data['report_type'] ?? '',
            'start_date' => $data['start_date'] ?? '',
            'end_date' => $data['end_date'] ?? '',
        ]);
        
        return redirect()->to($url);
    }
}
