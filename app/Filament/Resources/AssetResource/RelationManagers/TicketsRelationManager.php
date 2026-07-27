<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TicketsRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $title = 'Riwayat Tiket IT & Maintenance';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_number')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Tgl Jadwal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_time_slot')
                    ->label('Waktu'),

                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Pelapor / User')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Kendala')
                    ->searchable()
                    ->limit(30),

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
                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak Work Order (PDF)')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
