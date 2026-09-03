<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisposeAsetResource\Pages;
use App\Models\Asset;
use App\Models\DisposeAset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DisposeAsetResource extends Resource
{
    protected static ?string $model = DisposeAset::class;

    protected static ?string $navigationIcon = 'heroicon-o-trash';

    protected static ?string $navigationLabel = 'Dispose Aset IT';

    protected static ?string $modelLabel = 'Dispose Aset IT';

    protected static ?string $pluralModelLabel = 'Dispose Aset IT';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Nomor & Unit Aset Yang Diajukan Disposal')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('disposal_number')
                            ->label('No. Disposal')
                            ->default(fn () => DisposeAset::generateDisposalNumber())
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('disposal_date')
                            ->label('Tanggal Pengajuan')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status Disposal')
                            ->options([
                                'pending' => 'Pending (Menunggu Persetujuan)',
                                'approved' => 'Approved (Disetujui Manager IT)',
                                'transferred_to_ga' => 'Transferred to GA (Serah Terima GA)',
                                'completed' => 'Completed (Selesai Penjualan/Pemusnahan)',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),

                    Forms\Components\Select::make('asset_id')
                        ->label('Pilih Unit Asset IT Yang Akan Diajukan Disposal')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search): array {
                            return Asset::query()
                                ->with('assetModel')
                                ->where('asset_tag', 'ILIKE', "%{$search}%")
                                ->orWhere('serial', 'ILIKE', "%{$search}%")
                                ->orWhere('primary_user', 'ILIKE', "%{$search}%")
                                ->orWhereHas('assetModel', function ($q) use ($search) {
                                    $q->where('name', 'ILIKE', "%{$search}%")
                                      ->orWhere('manufacturer', 'ILIKE', "%{$search}%");
                                })
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(function ($asset) {
                                    $bookVal = $asset->purchase_cost > 0 ? " [Nilai Buku Sisa: Rp " . number_format($asset->current_book_value, 0, ',', '.') . "]" : '';
                                    return [$asset->id => "{$asset->asset_tag} - {$asset->assetModel?->manufacturer} {$asset->assetModel?->name}{$bookVal}"];
                                })
                                ->toArray();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            $asset = Asset::with('assetModel')->find($value);
                            if (!$asset) return null;
                            $bookVal = $asset->purchase_cost > 0 ? " [Nilai Buku Sisa: Rp " . number_format($asset->current_book_value, 0, ',', '.') . "]" : '';
                            return "{$asset->asset_tag} - {$asset->assetModel?->manufacturer} {$asset->assetModel?->name}{$bookVal}";
                        })
                        ->live()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $asset = Asset::find($state);
                                if ($asset) {
                                    $set('asset_tag', $asset->asset_tag);
                                    $set('asset_name', "{$asset->assetModel?->manufacturer} {$asset->assetModel?->name}");
                                }
                            }
                        })
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('asset_tag')
                            ->label('ID Inventaris (Asset Tag)')
                            ->required(),

                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama / Model Barang')
                            ->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Keterangan Kerusakan & Kategori Disposal')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('disposal_type')
                            ->label('Proses / Kategori Disposal')
                            ->options([
                                'sale' => 'Penjualan Aset',
                                'scrap' => 'Scrap / Afkir',
                                'trade_in' => 'Trade-In / Tukar Tambah',
                            ])
                            ->default('sale')
                            ->required()
                            ->live(),

                        Forms\Components\TextInput::make('estimated_salvage_value')
                            ->label('Estimasi Nilai Jual / Salvage Value (Jika Ada)')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0'),
                    ]),

                    Forms\Components\Textarea::make('disposal_reason')
                        ->label('Keterangan & Alasan Kerusakan Aset')
                        ->placeholder('Misal: Laptop mati total, motherboard konslet, layar pecah, dan biaya perbaikan tidak ekonomis.')
                        ->rows(4)
                        ->required(),

                    Forms\Components\Select::make('berita_acara_id')
                        ->label('Lampiran Berita Acara IT Terkait (Opsional)')
                        ->relationship('beritaAcara', 'letter_number')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->letter_number} - {$record->title} (" . ($record->letter_date ? $record->letter_date->format('d/m/Y') : '') . ")")
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->helperText('Pilih Berita Acara IT jika proses disposal ini merujuk ke Berita Acara kerusakan/kehilangan sebelumnya.')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Informasi Pembeli & Kesepakatan Harga (Khusus Penjualan / Scrap)')
                ->description('Lengkapi identitas pembeli/vendor rekanan untuk pembuatan dokumen Serah Terima & Quotation Penjualan.')
                ->collapsible()
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('buyer_name')
                            ->label('Nama Pembeli / Rekanan Vendor')
                            ->placeholder('Misal: CV Berkah Jaya / Bapak Hendra'),
                        Forms\Components\TextInput::make('buyer_contact')
                            ->label('Kontak / No. HP')
                            ->placeholder('Misal: 0812-xxxx-xxxx'),
                        Forms\Components\TextInput::make('selling_price')
                            ->label('Harga Kesepakatan Penjualan (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->placeholder('0'),
                    ]),
                    Forms\Components\TextInput::make('buyer_address')
                        ->label('Alamat Pembeli / Lokasi Pengambilan')
                        ->placeholder('Misal: Jl. Raya Industri No. 12, Cikarang')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Penanggung Jawab & Serah Terima GA')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('created_by_name')
                            ->label('Dibuat oleh (PIC IT)')
                            ->default(fn () => Auth::user()?->name ?? 'Bambang Yulianto')
                            ->required(),

                        Forms\Components\TextInput::make('spv_name')
                            ->label('Supervisor IT')
                            ->default('Supervisor IT'),

                        Forms\Components\TextInput::make('manager_name')
                            ->label('Manager IT')
                            ->default('SETYADI CANDRAWINATA'),

                        Forms\Components\TextInput::make('ga_recipient_name')
                            ->label('Penerima GA (General Affairs)')
                            ->default('General Affairs (GA)'),
                    ]),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Foto Asset & Bukti Kerusakan (Wajib Upload Foto Fisik Unit)')
                        ->disk('public')
                        ->directory('disposal-attachments')
                        ->visibility('public')
                        ->multiple()
                        ->image()
                        ->imagePreviewHeight('250')
                        ->openable()
                        ->downloadable()
                        ->previewable()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                        ->maxSize(10240),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('disposal_number')
                    ->label('No. Disposal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('disposal_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('ID Inventaris')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('disposal_type')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'sale' => 'success',
                        'trade_in' => 'info',
                        'scrap' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'sale' => 'Penjualan',
                        'trade_in' => 'Trade-In',
                        'scrap' => 'Scrap / Afkir',
                        'destruction' => 'Pemusnahan',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'transferred_to_ga' => 'primary',
                        'completed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'pending' => 'Pending',
                        'approved' => 'Disetujui IT',
                        'transferred_to_ga' => 'Diserahkan GA',
                        'completed' => 'Completed',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('pdf_disposal')
                    ->label('Form Disposal')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('dispose-asets.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pdf_serah_terima')
                    ->label('Serah Terima')
                    ->icon('heroicon-o-document-check')
                    ->color('primary')
                    ->url(fn ($record) => route('dispose-asets.pdf-serah-terima', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pdf_quotation')
                    ->label('Quotation')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('warning')
                    ->visible(fn ($record) => in_array($record->disposal_type, ['sale', 'scrap']))
                    ->url(fn ($record) => route('dispose-asets.pdf-quotation', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDisposeAsets::route('/'),
            'create' => Pages\CreateDisposeAset::route('/create'),
            'edit' => Pages\EditDisposeAset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['asset']);
    }
}
