<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CheckoutResource\Pages;
use App\Models\Checkout;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CheckoutResource extends Resource
{
    protected static ?string $model = Checkout::class;

    protected static ?string $navigationLabel = 'Asset History';

    protected static ?string $modelLabel = 'Asset History';

    protected static ?string $pluralModelLabel = 'Asset History';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?int $navigationSort = 3;

    // Read-only: history is created via the Checkout/Checkin actions on AssetResource, not here.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.asset_tag')
                    ->label('ID Asset')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('asset_unit_name')
                    ->label('Nama Unit / Perangkat')
                    ->getStateUsing(fn ($record) => $record->asset ? "{$record->asset->assetModel?->manufacturer} {$record->asset->assetModel?->name}" : '-')
                    ->searchable(query: function ($query, string $search) {
                        return $query->whereHas('asset.assetModel', function ($q) use ($search) {
                            $q->where('name', 'ilike', "%{$search}%")
                              ->orWhere('manufacturer', 'ilike', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('holder_name')
                    ->label('Checked out to (Pengguna)')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->where('primary_user', 'ilike', "%{$search}%")
                              ->orWhere('secondary_user', 'ilike', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('checked_out_at')
                    ->label('Tanggal Checkout')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Tanggal Checkin')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('Sedang Digunakan'),

                Tables\Columns\TextColumn::make('checkedOutByUser.name')
                    ->label('Checked out by')
                    ->getStateUsing(fn ($record) => $record->checkedOutByUser?->name ?? 'Admin'),

                Tables\Columns\TextColumn::make('checkedInByUser.name')
                    ->label('Checked in by')
                    ->getStateUsing(fn ($record) => $record->checked_in_at ? ($record->checkedInByUser?->name ?? 'Admin') : '-'),

                Tables\Columns\TextColumn::make('attachments_info')
                    ->label('Lampiran / Bukti')
                    ->getStateUsing(function ($record) {
                        $count = count($record->getAllAttachments());
                        return $count > 0 ? "{$count} Berkas / Foto" : '-';
                    })
                    ->url(function ($record) {
                        $files = $record->getAllAttachments();
                        return !empty($files[0]) ? asset('storage/' . $files[0]) : null;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-paper-clip')
                    ->color(fn ($record) => count($record->getAllAttachments()) > 0 ? 'primary' : 'gray'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('active')
                    ->label('Status Penggunaan')
                    ->placeholder('Semua Riwayat')
                    ->trueLabel('Sedang Digunakan (Active)')
                    ->falseLabel('Sudah Dikembalikan (Checked in)')
                    ->queries(
                        true: fn ($query) => $query->whereNull('checked_in_at'),
                        false: fn ($query) => $query->whereNotNull('checked_in_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf_handover')
                    ->label('Form Serah Terima (PDF)')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->url(fn ($record) => route('checkouts.pdf-handover', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('pdf_return')
                    ->label('Form Pengembalian (PDF)')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->checked_in_at !== null)
                    ->url(fn ($record) => route('checkouts.pdf-return', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\Action::make('view_attachments')
                    ->label('Lihat Lampiran')
                    ->icon('heroicon-o-paper-clip')
                    ->color('primary')
                    ->visible(fn ($record) => count($record->getAllAttachments()) > 0)
                    ->modalHeading('Lampiran & Dokumentasi Serah Terima / Pengembalian')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.components.attachments-modal', ['files' => $record->getAllAttachments()])),
            ])
            ->defaultSort('checked_out_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCheckouts::route('/'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'asset.assetModel',
                'checkedOutByUser:id,name',
                'checkedInByUser:id,name',
            ]);
    }
}
