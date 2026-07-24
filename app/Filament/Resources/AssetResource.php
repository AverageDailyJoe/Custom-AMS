<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers\CheckoutsRelationManager;
use App\Models\Asset;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Utama')
                ->schema([
                    Forms\Components\TextInput::make('asset_tag')
                        ->label('ID Inventaris / Tag')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Select::make('asset_model_id')
                        ->label('Type / Model Unit')
                        ->relationship('assetModel', 'name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->manufacturer} {$record->name}")
                        ->required()
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Kategori')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Select::make('type')
                                        ->label('Tipe Kategori')
                                        ->options([
                                            'asset' => 'Asset',
                                            'accessory' => 'Accessory',
                                            'consumable' => 'Consumable',
                                            'license' => 'License',
                                            'component' => 'Component',
                                        ])
                                        ->default('asset')
                                        ->required(),
                                ]),
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Model Unit')
                                ->placeholder('Contoh: PC Desktop, Latitude 5420')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('manufacturer')
                                ->label('Merk / Manufaktur')
                                ->placeholder('Contoh: Dell, Custom, LG, ViewSonic')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('model_number')
                                ->label('Nomor Model')
                                ->maxLength(255),
                        ]),
                    Forms\Components\TextInput::make('serial')
                        ->label('Serial Number')
                        ->maxLength(255),
                    Forms\Components\Select::make('location_id')
                        ->label('Lokasi Utama')
                        ->relationship('location', 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Lokasi')
                                ->placeholder('Contoh: FACTORY, HEAD OFFICE')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Textarea::make('address')
                                ->label('Alamat / Keterangan')
                                ->maxLength(500),
                        ]),
                    Forms\Components\TextInput::make('room')
                        ->label('Ruangan / Detail Lokasi')
                        ->placeholder('Contoh: OFFICE ATAS, RUANGAN PRODUKSI'),
                    Forms\Components\TextInput::make('department')
                        ->label('Departemen')
                        ->placeholder('Contoh: PPIC, FINANCE & ACCOUNTING, PURCHASING'),
                ])->columns(2),

            Forms\Components\Section::make('Pengguna & Penanggung Jawab')
                ->schema([
                    Forms\Components\TextInput::make('primary_user')
                        ->label('Pengguna 1 (Utama)')
                        ->placeholder('Nama Pengguna Utama'),
                    Forms\Components\TextInput::make('secondary_user')
                        ->label('Pengguna 2 (Pendamping)')
                        ->placeholder('Nama Pengguna Cadangan / Pendamping'),
                ])->columns(2),

            Forms\Components\Section::make('Spesifikasi Hardware')
                ->schema([
                    Forms\Components\TextInput::make('processor')
                        ->label('Processor')
                        ->placeholder('Contoh: i3 Gen 10, Intel Pentium'),
                    Forms\Components\TextInput::make('ram')
                        ->label('RAM')
                        ->placeholder('Contoh: 8 GB, 4 GB'),
                    Forms\Components\TextInput::make('storage_hdd')
                        ->label('HDD')
                        ->placeholder('Contoh: 500 GB, 250 GB'),
                    Forms\Components\TextInput::make('storage_ssd')
                        ->label('SSD')
                        ->placeholder('Contoh: 256 GB, 128 GB'),
                    Forms\Components\TextInput::make('vga_card')
                        ->label('VGA Card')
                        ->placeholder('Contoh: NVIDIA GeForce GT 610'),
                ])->columns(3),

            Forms\Components\Section::make('Informasi Monitor')
                ->schema([
                    Forms\Components\TextInput::make('monitor_id')
                        ->label('ID Monitor')
                        ->placeholder('Contoh: GTK-M-01-01-01'),
                    Forms\Components\TextInput::make('monitor_spec')
                        ->label('Monitor (Merk / Ukuran)')
                        ->placeholder('Contoh: VIEWSONIC 19", LG 14"'),
                ])->columns(2),

            Forms\Components\Section::make('Status & Pembelian')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status Asset')
                        ->options([
                            'in_stock' => 'In stock (Tersedia)',
                            'checked_out' => 'Checked out (Digunakan)',
                            'in_repair' => 'In repair (Perbaikan)',
                            'archived' => 'Archived (Diarsipkan)',
                        ])
                        ->disabled(fn (?Asset $record) => $record?->isCheckedOut() ?? false)
                        ->required(),
                    Forms\Components\Select::make('condition')
                        ->label('Kondisi Asset')
                        ->options([
                            'bagus' => 'Bagus',
                            'rusak' => 'Rusak',
                            'maintenance' => 'Perlu Maintenance',
                        ])
                        ->default('bagus')
                        ->required(),
                    Forms\Components\TextInput::make('purchase_year')
                        ->label('Tahun Pembelian')
                        ->numeric()
                        ->placeholder('Contoh: 2020'),
                    Forms\Components\DatePicker::make('purchase_date')
                        ->label('Tanggal Pembelian'),
                    Forms\Components\TextInput::make('purchase_cost')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('warranty')
                        ->label('Garansi')
                        ->placeholder('Garansi'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Keterangan / Catatan')
                        ->columnSpanFull(),
                ])->columns(3),
            Forms\Components\Section::make('Dokumen Fisik & Manual Attachment')
                ->description('Upload dokumen fisik pendukung (Invoice, Manual Book, Kartu Garansi, Nota Pembelian) untuk mencegah hilang / rusaknya arsip fisik.')
                ->icon('heroicon-o-paper-clip')
                ->schema([
                    Forms\Components\FileUpload::make('attachments')
                        ->label('Upload Dokumen Fisik (Foto / PDF)')
                        ->directory('asset-documents')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                        ->maxSize(10240)
                        ->openable()
                        ->downloadable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('ID Inventaris')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial')
                    ->label('Serial Number')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('assetModel.name')
                    ->label('Model')
                    ->formatStateUsing(fn ($record) => "{$record->assetModel?->manufacturer} {$record->assetModel?->name}")
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assetModel.category.name')
                    ->label('Category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'in_stock' => 'success',
                        'checked_out' => 'warning',
                        'in_repair' => 'danger',
                        'archived' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('location.name')
                    ->label('Location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('holder_name')
                    ->label('Checked out to')
                    ->searchable(['primary_user', 'secondary_user'])
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('department')
                    ->label('Departemen')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('room')
                    ->label('Ruangan')
                    ->searchable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'in_stock' => 'In stock',
                    'checked_out' => 'Checked out',
                    'in_repair' => 'In repair',
                    'archived' => 'Archived',
                ]),
                Tables\Filters\SelectFilter::make('location_id')
                    ->relationship('location', 'name')
                    ->label('Location'),
            ])
            ->actions([
                Tables\Actions\Action::make('checkout')
                    ->label('Checkout')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->visible(fn (Asset $record) => $record->status === 'in_stock' && empty($record->primary_user))
                    ->form([
                        Forms\Components\TextInput::make('primary_user')
                            ->label('Pengguna 1 (Utama)')
                            ->required(),
                        Forms\Components\TextInput::make('secondary_user')
                            ->label('Pengguna 2 (Pendamping)'),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Checkout'),
                        Forms\Components\FileUpload::make('checkout_attachments')
                            ->label('Lampiran / Bukti Serah Terima (Bisa Banyak Foto / PDF)')
                            ->directory('checkout-attachments')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                            ->maxSize(10240)
                            ->openable()
                            ->downloadable(),
                    ])
                    ->action(function (Asset $record, array $data) {
                        $record->checkoutToUser(
                            $data['primary_user'],
                            $data['secondary_user'] ?? null,
                            null,
                            $data['notes'] ?? null,
                            $data['checkout_attachments'] ?? null
                        );

                        Notification::make()
                            ->title("Asset {$record->asset_tag} checked out to {$data['primary_user']}")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('checkin')
                    ->label('Checkin')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('success')
                    ->visible(fn (Asset $record) => $record->status === 'checked_out' || !empty($record->primary_user))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Pengembalian (Checkin)'),
                        Forms\Components\FileUpload::make('checkin_attachments')
                            ->label('Lampiran / Bukti Pengembalian (Bisa Banyak Foto / PDF)')
                            ->directory('checkin-attachments')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                            ->maxSize(10240)
                            ->openable()
                            ->downloadable(),
                    ])
                    ->action(function (Asset $record, array $data) {
                        $record->checkin(
                            $data['notes'] ?? null,
                            $data['checkin_attachments'] ?? null
                        );

                        Notification::make()
                            ->title("Asset {$record->asset_tag} checked in")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CheckoutsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'assetModel.category',
                'location',
                'checkouts',
            ]);
    }
}
