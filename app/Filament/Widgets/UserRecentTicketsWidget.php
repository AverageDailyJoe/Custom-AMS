<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class UserRecentTicketsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'user';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Ticket::query()
                    ->where('created_by', Auth::id())
                    ->latest()
                    ->limit(5)
            )
            ->heading('Status Tiket Terbaru Saya')
            ->columns([
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Pelaporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Judul Kendala')
                    ->limit(35),

                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Perangkat')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label('Teknisi IT')
                    ->placeholder('Belum Ditugaskan'),

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
                        'open' => 'Open (Tiket Baru)',
                        'scheduled' => 'Terjadwal IT',
                        'in_progress' => 'In Progress',
                        'pending_sparepart' => 'Menunggu Sparepart',
                        'rescheduled' => 'Jadwal Diubah',
                        'resolved' => 'Selesai (Resolved)',
                        'closed' => 'Closed',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak Tiket')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),
            ]);
    }
}
