<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = '5 Tiket Layanan IT Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(Ticket::query()->latest('created_at')->limit(5))
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable(),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Tgl Jadwal')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('scheduled_time_slot')
                    ->label('Waktu'),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Target Selesai (SLA)')
                    ->date('d M Y')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Pelapor'),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Kendala')
                    ->limit(25),

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
                    ->label('Status')
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
            ->actions([
                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
