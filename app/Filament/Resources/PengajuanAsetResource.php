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

    protected static ?string $navigationLabel = 'Pengajuan Baru';

    protected static ?string $modelLabel = 'Pengajuan Baru';

    protected static ?string $pluralModelLabel = 'Pengajuan Baru';

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

                    Forms\Components\Grid::make(2)->schema([
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

                        Forms\Components\Select::make('company')
                            ->label('Perusahaan (Kop Surat)')
                            ->options([
                                'PT. GONDOWANGI TRADISIONAL KOSMETIKA' => 'PT. Gondowangi Tradisional Kosmetika',
                                'PT. BUANAINDAH MARKETAMA SEJAHTERA' => 'PT. Buanaindah Marketama Sejahtera',
                            ])
                            ->default('PT. GONDOWANGI TRADISIONAL KOSMETIKA')
                            ->required(),

                        Forms\Components\Select::make('asset_id')
                            ->label('Target Aset Terkait (Opsional / Upgrade / Servis)')
                            ->options(function () {
                                return \App\Models\Asset::with(['assetModel', 'location'])
                                    ->orderBy('asset_tag')
                                    ->get()
                                    ->mapWithKeys(function ($asset) {
                                        $holder = !empty($asset->primary_user)
                                            ? $asset->primary_user
                                            : ($asset->location?->name ?? 'In Stock');
                                        $modelName = $asset->assetModel?->name ?? 'Aset';
                                        return [$asset->id => "{$asset->asset_tag} - {$modelName} ({$holder})"];
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->nullable()
                            ->placeholder('— Tidak Ada (Pengajuan Baru) —')
                            ->columnSpanFull()
                            ->helperText('Pilih unit aset jika pengajuan ini ditujukan untuk upgrade atau penggantian komponen aset eksisting.'),
                    ]),
                ]),

            Forms\Components\Section::make('📋 [PPB] Permohonan Pembelian Barang (Pengajuan Awal & Budget)')
                ->description('Bagian ini diisi saat pertama kali mengajukan budget / permohonan uang muka ke atasan.')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul / Perihal Pengajuan Aset (PPB)')
                        ->placeholder('Misal: Pengajuan 5 Unit Laptop Lenovo T480')
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
                        ->label('Rincian Barang & Estimasi Biaya PPB (Pengajuan Awal)')
                        ->addActionLabel('Tambah Item PPB (+)')
                        ->reorderable()
                        ->cloneable()
                        ->collapsible()
                        ->defaultItems(1)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nama / Judul Item (Di PPB)')
                                    ->placeholder('Misal: Laptop Intel Core i7')
                                    ->required(),

                                Forms\Components\Select::make('item_type')
                                    ->label('Jenis Perangkat / Asset')
                                    ->options([
                                        'Laptop' => 'Laptop / Notebook',
                                        'PC Desktop' => 'PC Desktop Unit',
                                        'Monitor' => 'Monitor Display',
                                        'Printer/Scanner' => 'Printer / Scanner',
                                        'Smartphone' => 'Handphone / Smartphone',
                                        'Sparepart dan Komponen utama' => 'Sparepart & Komponen Utama (RAM / SSD / HDD / Mobo)',
                                        'Peripheral IT' => 'Peripheral IT (Keyboard / Mouse / Headset / Adapter)',
                                        'Aksesoris IT' => 'Aksesoris IT & Kabel Transmisi (RJ45 / Kabel LAN / Ties)',
                                        'Peralatan IT' => 'Peralatan & Tools IT (Solder / Tang Crimping / LAN Tester / Obeng Set)',
                                        'Network Equipment' => 'Perangkat Jaringan & Infrastructure (Switch / Router / AP)',
                                        'Software/Lisensi' => 'Software / Lisensi Aplikasi',
                                        'Lainnya' => 'Lain-lain',
                                    ])
                                    ->default('Laptop')
                                    ->required(),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah Unit (Estimasi)')
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
                                    ->placeholder('Misal: Intel Core i7, RAM 16GB, SSD 512GB'),
                            ]),
                        ]),

                    Forms\Components\TextInput::make('uang_muka')
                        ->label('Nominal Pengajuan Uang Muka PPB (Rp)')
                        ->helperText('Nominal uang muka yang dimohonkan/diterima di awal.')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('0')
                        ->default(0)
                        ->nullable(),
                ]),

            Forms\Components\Section::make('📄 [LBS] Laporan Biaya Settlement (Realisasi Belanja & Nota/Faktur)')
                ->description('Bagian ini diisi setelah barang selesai dibeli untuk mempertanggungjawabkan realisasi nota/faktur pajak aktual & selisih/balance transfer.')
                ->collapsible()
                ->schema([
                    Forms\Components\Toggle::make('has_lbs')
                        ->label('Aktifkan / Isi Realisasi Belanja LBS (Settlement Nota/Faktur)')
                        ->helperText('Aktifkan jika barang sudah dibeli dan ingin mencetak LBS dengan rincian nota/faktur aktual.')
                        ->live(),

                    Forms\Components\Repeater::make('lbs_items')
                        ->label('Rincian Barang & Harga Realisasi Aktual (Di Nota / Faktur Pajak)')
                        ->addActionLabel('Tambah Item Realisasi LBS (+)')
                        ->reorderable()
                        ->cloneable()
                        ->collapsible()
                        ->defaultItems(1)
                        ->columnSpanFull()
                        ->visible(fn (\Filament\Forms\Get $get) => (bool) $get('has_lbs'))
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Nama Barang di Nota / Faktur')
                                    ->placeholder('Misal: Laptop Lenovo T480 14"')
                                    ->required(),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah Unit Aktual')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live(),

                                Forms\Components\TextInput::make('unit_cost')
                                    ->label('Harga Dasar Per Unit (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->live(),
                            ]),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('ppn_amount')
                                    ->label('Total PPN / Pajak Tambahan Nota (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->helperText('Nominal PPN 11% / Pajak faktur jika ada.'),

                                Forms\Components\TextInput::make('specification')
                                    ->label('Keterangan Nota / No. Faktur / Garansi')
                                    ->placeholder('Misal: Nota Star Com 01801 / Faktur 0400260036508963'),
                            ]),
                        ]),

                    Forms\Components\Repeater::make('additional_fees')
                        ->label('Rincian Biaya Tambahan Transaksi (Ongkir, Fee Layanan, Handling)')
                        ->addActionLabel('Tambah Biaya Lain (+)')
                        ->reorderable()
                        ->cloneable()
                        ->collapsible()
                        ->defaultItems(0)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Keterangan / Nama Biaya')
                                    ->placeholder('Misal: Biaya Layanan Tokopedia, Packing Kayu, Asuransi Pengiriman')
                                    ->required(),

                                Forms\Components\TextInput::make('amount')
                                    ->label('Nominal Biaya (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->placeholder('0')
                                    ->required()
                                    ->live(),
                            ]),
                        ]),

                    Forms\Components\TextInput::make('adjustment_amount')
                        ->label('Selisih / Balance Settlement LBS (Rp)')
                        ->helperText('Nominal kurang/lebih bayar. Diisi positif (+) jika Gondowangi kurang bayar (reimburse ke user). Diisi minus (-) jika ada sisa uang muka dikembalikan.')
                        ->numeric()
                        ->prefix('Rp')
                        ->placeholder('0')
                        ->default(0)
                        ->nullable(),

                    Forms\Components\Textarea::make('lbs_notes')
                        ->label('Catatan Keterangan LBS / Settlement')
                        ->placeholder('Misal: Realisasi pembelian 5 unit laptop di Star Com Harco. Selisih Rp 250.000 mohon ditransfer ke rekening user.')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Alasan & Dokumen Lampiran')
                ->schema([
                    Forms\Components\Textarea::make('reason')
                        ->label('Alasan & Keperluan Pengajuan Baru')
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

            if (isset($data['additional_fees']) && is_array($data['additional_fees'])) {
                foreach ($data['additional_fees'] as $fee) {
                    $totalCostSum += (float) ($fee['amount'] ?? 0);
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

                Tables\Columns\TextColumn::make('asset.asset_tag')
                    ->label('Target Aset')
                    ->formatStateUsing(function ($record) {
                        if (!$record->asset) {
                            return '-';
                        }
                        $holder = !empty($record->target_asset_holder)
                            ? $record->target_asset_holder
                            : ($record->asset->holder_name !== '-' ? $record->asset->holder_name : ($record->asset->location?->name ?? 'Aset'));
                        return "{$record->asset->asset_tag} ({$holder})";
                    })
                    ->searchable()
                    ->toggleable(),

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
            ->filters([
                Tables\Filters\SelectFilter::make('requester_name')
                    ->label('Filter Pemohon')
                    ->options(fn () => PengajuanAset::distinct()->whereNotNull('requester_name')->pluck('requester_name', 'requester_name')->toArray())
                    ->searchable(),

                Tables\Filters\SelectFilter::make('item_type')
                    ->label('Filter Kategori Barang')
                    ->options([
                        'Laptop' => 'Laptop / Notebook',
                        'PC Desktop' => 'PC Desktop Unit',
                        'Monitor' => 'Monitor Display',
                        'Printer' => 'Printer / Scanner',
                        'Smartphone' => 'Handphone / Smartphone',
                        'Komponen Utama' => 'Sparepart & Komponen Utama (RAM / SSD / Mobo)',
                        'Peripheral IT' => 'Peripheral IT (Keyboard / Mouse / Adapter)',
                        'Aksesoris IT' => 'Aksesoris IT & Kabel Transmisi (RJ45 / Kabel LAN / Ties)',
                        'Peralatan IT' => 'Peralatan & Tools IT (Solder / Tang Crimping / LAN Tester / Obeng Set)',
                        'Network Equipment' => 'Perangkat Jaringan & Infrastructure (Switch / Router / AP)',
                        'Software' => 'Software / Lisensi',
                        'Lainnya' => 'Lain-lain',
                    ])
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $val = $data['value'];
                            $query->where(function ($q) use ($val) {
                                $q->where('item_type', $val)
                                  ->orWhere('specification_requested', 'ILIKE', "%{$val}%")
                                  ->orWhereRaw("items::text ILIKE ?", ["%\"item_type\":\"{$val}\"%"]);
                            });
                        }
                    }),

                Tables\Filters\SelectFilter::make('asset_id')
                    ->label('Filter Target Aset')
                    ->relationship('asset', 'asset_tag')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('requester_department')
                    ->label('Filter Departemen')
                    ->options(fn () => PengajuanAset::distinct()->whereNotNull('requester_department')->pluck('requester_department', 'requester_department')->toArray())
                    ->searchable(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending (Menunggu Approval)',
                        'approved' => 'Approved (Disetujui)',
                        'rejected' => 'Rejected (Ditolak)',
                        'completed' => 'Completed (Selesai Pengadaan)',
                    ]),
            ])
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
