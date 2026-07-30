<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanAsetResource\Pages;
use App\Models\PengajuanAset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PengajuanAsetResource extends Resource
{
    protected static ?string $model = PengajuanAset::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationLabel = 'Pengajuan Aset Baru';

    protected static ?string $modelLabel = 'Pengajuan Aset Baru';

    protected static ?string $pluralModelLabel = 'Pengajuan Aset Baru';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Nomor & Identitas Pemohon')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('request_number')
                            ->label('No. Pengajuan')
                            ->default(fn () => PengajuanAset::generateRequestNumber())
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('request_date')
                            ->label('Tanggal Pengajuan')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Pengajuan')
                            ->options([
                                'pending' => 'Pending (Menunggu Approval)',
                                'approved' => 'Approved (Disetujui)',
                                'rejected' => 'Rejected (Ditolak)',
                                'completed' => 'Completed (Selesai Pengadaan)',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('requester_name')
                            ->label('Nama Pemohon')
                            ->default(fn () => Auth::user()?->name ?? '')
                            ->required(),

                        Forms\Components\TextInput::make('requester_department')
                            ->label('Departemen Pemohon')
                            ->placeholder('Misal: Digital Marketing / Finance / IT')
                            ->required(),

                        Forms\Components\TextInput::make('area')
                            ->label('Area Lokasi')
                            ->placeholder('Misal: HQ / Head Office, Factory Jababeka')
                            ->default('HQ / Head Office'),
                    ]),
                ]),

            Forms\Components\Section::make('Item & Rincian Pengajuan')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Pengajuan')
                        ->placeholder('Misal: Pengajuan Laptop Baru untuk Staff Digital Marketing')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\Select::make('item_type')
                            ->label('Jenis Perangkat / Asset')
                            ->options([
                                'Laptop' => 'Laptop / Notebook',
                                'PC Desktop' => 'PC Desktop Unit',
                                'Monitor' => 'Monitor Display',
                                'Printer' => 'Printer / Scanner',
                                'Smartphone' => 'Handphone / Smartphone',
                                'Komponen Utama' => 'Sparepart & Komponen Utama (RAM / SSD / HDD / Mobo)',
                                'Peripheral IT' => 'Peripheral IT (Keyboard / Mouse / Headset / Adapter)',
                                'Aksesoris IT' => 'Aksesoris IT & Kabel Transmisi',
                                'Software' => 'Software / Lisensi Aplikasi',
                                'Lainnya' => 'Lain-lain',
                            ])
                            ->default('Laptop')
                            ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah Unit')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('priority')
                            ->label('Tingkat Prioritas')
                            ->options([
                                'low' => 'Low (Biasa)',
                                'medium' => 'Medium (Sedang)',
                                'high' => 'High (Tinggi)',
                                'urgent' => 'Urgent (Sangat Mendesak)',
                            ])
                            ->default('medium')
                            ->required(),
                    ]),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('estimated_cost')
                            ->label('Estimasi Biaya Per Unit (Rp)')
                            ->helperText(fn (Forms\Get $get) => 'Total Biaya: Rp ' . number_format(((float) $get('estimated_cost')) * ((int) ($get('quantity') ?? 1)), 0, ',', '.'))
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0')
                            ->live(),

                        Forms\Components\TextInput::make('approver_name')
                            ->label('Nama Atasan (Mengetahui)')
                            ->default('SETYADI CANDRAWINATA'),

                        Forms\Components\TextInput::make('approver_title')
                            ->label('Jabatan Atasan')
                            ->default('GM Finance & Operations'),
                    ]),
                ]),

            Forms\Components\Section::make('Spesifikasi Teknis & Alasan Pengajuan')
                ->schema([
                    Forms\Components\TextInput::make('specification_requested')
                        ->label('Spesifikasi Teknis Yang Diminta / Dibutuhkan')
                        ->placeholder('Misal: Intel Core i7, RAM 16GB, SSD 512GB, Windows 11 Pro')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('reason')
                        ->label('Alasan & Keperluan Pengajuan Aset Baru')
                        ->placeholder('Misal: Untuk penambahan karyawan baru di divisi Marketing atau unit lama sudah rusak berat.')
                        ->rows(4)
                        ->required(),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Dokumen / Lampiran Pendukung (Nota Dinas, Proposal, Penawaran Harga)')
                        ->directory('pengajuan-aset-attachments')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->openable()
                        ->downloadable()
                        ->previewable()
                        ->image()
                        ->imagePreviewHeight('250')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                        ->maxSize(10240),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('No. Pengajuan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('request_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Pengajuan')
                    ->searchable()
                    ->limit(28),

                Tables\Columns\TextColumn::make('requester_name')
                    ->label('Pemohon')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item_type')
                    ->label('Item'),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Qty')
                    ->formatStateUsing(fn ($state) => "{$state} Unit"),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'primary',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('pdf_ppb')
                    ->label('Cetak PPB')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->url(fn ($record) => route('pengajuan-asets.pdf-ppb', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pdf_lbs')
                    ->label('Cetak LBS')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->url(fn ($record) => route('pengajuan-asets.pdf-lbs', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengajuanAsets::route('/'),
            'create' => Pages\CreatePengajuanAset::route('/create'),
            'edit' => Pages\EditPengajuanAset::route('/{record}/edit'),
        ];
    }
}
