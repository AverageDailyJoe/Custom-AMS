<?php

namespace App\Filament\Widgets;

use App\Models\Checkout;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentAssetActivityWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '5 Riwayat Transaksi & Aktivitas Aset IT Terbaru (Checkout / Checkin)';

    public function table(Table $table): Table
    {
        return $table
            ->query(Checkout::query()->with(['asset.assetModel'])->latest('updated_at')->limit(5))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('asset.asset_tag')
                    ->label('ID Inventaris')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asset.assetModel.name')
                    ->label('Model Unit')
                    ->formatStateUsing(fn ($record) => "{$record->asset?->assetModel?->manufacturer} {$record->asset?->assetModel?->name}"),

                Tables\Columns\TextColumn::make('holder_name')
                    ->label('Pengguna (Utama / Pendamping)'),

                Tables\Columns\TextColumn::make('checked_out_at')
                    ->label('Tgl Checkout')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('checked_in_at')
                    ->label('Status Aktivitas')
                    ->badge()
                    ->color(function ($record) {
                        if ($record->asset?->status === 'disposed') {
                            return 'danger';
                        }
                        return $record->checked_in_at ? 'success' : 'warning';
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->asset?->status === 'disposed') {
                            return 'Aset Disposed';
                        }
                        return $record->checked_in_at ? 'Checkin Kembali' : 'Checkout ke User';
                    }),

                Tables\Columns\TextColumn::make('checkout_notes')
                    ->label('Catatan')
                    ->limit(30)
                    ->placeholder('-'),
            ])
            ->recordClasses(fn ($record) => $record->asset?->status === 'disposed' ? 'opacity-60 line-through' : null)
            ->actions([
                Tables\Actions\Action::make('pdf_handover')
                    ->label('Form Serah Terima')
                    ->icon('heroicon-o-document-text')
                    ->color('warning')
                    ->url(fn ($record) => route('checkouts.pdf-handover', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('pdf_return')
                    ->label('Form Pengembalian')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('danger')
                    ->visible(fn ($record) => $record->checked_in_at !== null)
                    ->url(fn ($record) => route('checkouts.pdf-return', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
