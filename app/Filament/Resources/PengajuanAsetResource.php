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

            Forms\Components\Section::make('Item & Rincian Pengajuan Barang')
                ->description('Gunakan tombol "+ Tambah Item / Varian Perangkat" di bawah jika terdapat lebih dari satu barang atau varian.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul / Perihal Pengajuan Aset')
                        ->placeholder('Misal: Pengajuan Laptop & Perangkat IT Baru untuk Digital Marketing')
                        ->required()
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(3)->schema([
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

                        Forms\Components\TextInput::make('approver_name')
                            ->label('Nama Atasan (Mengetahui)')
                            ->default('SETYADI CANDRAWINATA'),

                        Forms\Components\TextInput::make('approver_title')
                            ->label('Jabatan Atasan')
                            ->default('GM Finance & Operations'),
                    ]),

                    Forms\Components\Repeater::make('items')
                        ->label('Rincian Barang & Spesifikasi Teknis')
                        ->addActionLabel('Tambah Item / Varian Perangkat (+)')
                        ->reorderable()
                        ->cloneable()
                        ->collapsible()
                        ->defaultItems(1)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nama / Judul Item')
                                    ->placeholder('Misal: Laptop Intel Core i7 14th Gen')
                                    ->required(),

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
                            ]),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('estimated_cost')
                                    ->label('Estimasi Biaya Per Unit (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->live(),

                                Forms\Components\TextInput::make('specification')
                                    ->label('Spesifikasi Teknis Yang Diminta')
                                    ->placeholder('Misal: Intel Core i7, RAM 16GB, SSD 512GB, Windows 11 Pro'),
                            ]),
                    Forms\Components\Section::make('Rincian Biaya Tambahan Transaksi (Ongkir, Biaya Layanan, Asuransi)')
                        ->description('Catatkan biaya resmi transaksi toko online/vendor agar 100% transparan dan tercantum pada kolom nominal PPB & LBS.')
                        ->collapsible()
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Ongkos Kirim & Asuransi Pengiriman (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->default(0)
                                    ->live(),

                                Forms\Components\TextInput::make('service_fee')
                                    ->label('Biaya Layanan & Aplikasi Platform (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->default(0)
                                    ->live(),

                                Forms\Components\TextInput::make('other_fee')
                                    ->label('Biaya Penanganan / Handling & Admin Fee (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->default(0)
                                    ->live(),
                            ]),
                        ]),
                ]),

            Forms\Components\Section::make('Alasan & Dokumen Lampiran')
                ->schema([
                    Forms\Components\Textarea::make('reason')
                        ->label('Alasan & Keperluan Pengajuan Aset Baru')
                        ->placeholder('Misal: Untuk penambahan karyawan baru di divisi Marketing atau unit lama sudah rusak berat.')
                        ->rows(4)
                        ->required(),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Dokumen / Lampiran Pendukung (Nota Dinas, Proposal, Penawaran Harga)')
                        ->disk('public')
                        ->directory('pengajuan-aset-attachments')
                        ->visibility('public')
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

    public static function syncLegacyColumns(array $data): array
    {
        if (isset($data['items']) && is_array($data['items']) && count($data['items']) > 0) {
            $firstItem = $data['items'][0];
            $data['item_type'] = $firstItem['item_type'] ?? 'Laptop';
            
            $totalQty = 0;
            $totalCostSum = 0;
            $specs = [];
            foreach ($data['items'] as $item) {
                $q = (int) ($item['quantity'] ?? 1);
                if ($q < 1) $q = 1;
                $c = (float) ($item['estimated_cost'] ?? 0);
                $totalQty += $q;
                $totalCostSum += ($c * $q);
                if (!empty($item['specification'])) {
                    $specs[] = ($item['title'] ?? 'Item') . ': ' . $item['specification'];
                }
            }
            $shipping = (float) ($data['shipping_cost'] ?? 0);
            $service = (float) ($data['service_fee'] ?? 0);
            $other = (float) ($data['other_fee'] ?? 0);
            $totalCostSum += ($shipping + $service + $other);

            $data['quantity'] = $totalQty > 0 ? $totalQty : 1;
            $data['estimated_cost'] = $totalCostSum;
            $data['specification_requested'] = !empty($specs) ? implode(" | ", $specs) : ($firstItem['specification'] ?? null);
        }
        return $data;
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
