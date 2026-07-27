<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CheckoutsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkouts';

    protected static ?string $title = 'Checkout history';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('holder_name')
                    ->label('Pengguna (Utama / Pendamping)')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->where('primary_user', 'ilike', "%{$search}%")
                              ->orWhere('secondary_user', 'ilike', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('checked_out_at')->label('Tanggal Checkout')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('checked_in_at')->label('Tanggal Checkin')->dateTime()->placeholder('Sedang Digunakan'),
                Tables\Columns\TextColumn::make('checkedOutByUser.name')
                    ->label('Petugas (Admin)')
                    ->getStateUsing(fn ($record) => $record->checkedOutByUser?->name ?? $record->checkedInByUser?->name ?? 'Admin'),
                Tables\Columns\TextColumn::make('attachments_info')
                    ->label('Lampiran / Bukti')
                    ->getStateUsing(function ($record) {
                        $count = $record->getAllAttachmentsCount();
                        return $count > 0 ? "{$count} Berkas / Foto" : '-';
                    })
                    ->url(function ($record) {
                        $files = $record->getAllAttachments();
                        return !empty($files[0]) ? asset('storage/' . $files[0]) : null;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-paper-clip')
                    ->color(fn ($record) => $record->getAllAttachmentsCount() > 0 ? 'primary' : 'gray'),
                Tables\Columns\TextColumn::make('checkout_notes')->label('Catatan')->limit(40),
            ])
            ->defaultSort('checked_out_at', 'desc')
            ->headerActions([])
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
                    ->modalHeading('Lampiran & Dokumentasi Serah Terima')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.components.attachments-modal', ['files' => $record->getAllAttachments()])),
            ]);
    }
}
