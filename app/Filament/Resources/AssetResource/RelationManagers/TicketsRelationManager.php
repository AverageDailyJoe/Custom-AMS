<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use App\Models\Ticket;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $title = 'Riwayat Tiket IT, Perbaikan & Maintenance';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_number')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Tgl Jadwal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Pelapor / User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Kendala Dilaporkan')
                    ->searchable()
                    ->limit(35),

                Tables\Columns\TextColumn::make('resolution_notes')
                    ->label('Solusi IT / Pengerjaan')
                    ->limit(40)
                    ->placeholder('Masih Proses / Pending'),

                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label('Teknisi IT')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'low' => 'info',
                        'medium' => 'primary',
                        'high' => 'warning',
                        'critical' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Tiket')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'open' => 'gray',
                        'scheduled' => 'info',
                        'in_progress' => 'warning',
                        'pending_sparepart' => 'danger',
                        'rescheduled' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'open' => 'Open',
                        'scheduled' => 'Terjadwal',
                        'in_progress' => 'In Progress',
                        'pending_sparepart' => 'Pending Part',
                        'rescheduled' => 'Rescheduled',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    }),
            ])
            ->defaultSort('scheduled_date', 'desc')
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view_ticket_detail')
                    ->label('Detail Solusi IT')
                    ->icon('heroicon-o-information-circle')
                    ->color('primary')
                    ->modalHeading(fn ($record) => "Rincian Tiket & Perbaikan: {$record->ticket_number}")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.components.ticket-detail-modal', ['ticket' => $record])),

                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak Work Order (PDF)')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
