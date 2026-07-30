<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MaintenanceScheduleWidget extends BaseWidget
{
    protected static ?string $heading = '📅 Jadwal Preventive Maintenance IT (Perawatan Berkala)';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Asset::query()
                    ->with(['assetModel.category', 'location', 'tickets'])
                    ->where('status', '!=', 'disposed')
            )
            ->columns([
                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('ID Inventaris (Tag)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Model Perangkat')
                    ->formatStateUsing(fn ($record) => "{$record->assetModel?->manufacturer} {$record->assetModel?->name}")
                    ->searchable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('Lokasi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('holder_name')
                    ->label('Pengguna Utama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('next_maintenance_date')
                    ->label('Jadwal Maintenance Berikutnya')
                    ->state(function (Asset $record): string {
                        $lastTicket = $record->tickets
                            ->where('category', 'scheduled_service')
                            ->sortByDesc('scheduled_date')
                            ->first();

                        $baseDate = $lastTicket?->scheduled_date 
                            ? Carbon::parse($lastTicket->scheduled_date) 
                            : ($record->purchase_date ? Carbon::parse($record->purchase_date) : Carbon::parse($record->created_at));

                        $categoryName = strtolower($record->assetModel?->category?->name ?? '');
                        $intervalMonths = (str_contains($categoryName, 'server') || str_contains($categoryName, 'network')) ? 3 : 6;

                        $nextDate = $baseDate->copy()->addMonths($intervalMonths);
                        return $nextDate->format('d M Y');
                    })
                    ->badge()
                    ->color(function (Asset $record): string {
                        $lastTicket = $record->tickets
                            ->where('category', 'scheduled_service')
                            ->sortByDesc('scheduled_date')
                            ->first();

                        $baseDate = $lastTicket?->scheduled_date 
                            ? Carbon::parse($lastTicket->scheduled_date) 
                            : ($record->purchase_date ? Carbon::parse($record->purchase_date) : Carbon::parse($record->created_at));

                        $categoryName = strtolower($record->assetModel?->category?->name ?? '');
                        $intervalMonths = (str_contains($categoryName, 'server') || str_contains($categoryName, 'network')) ? 3 : 6;

                        $nextDate = $baseDate->copy()->addMonths($intervalMonths);
                        $daysDiff = Carbon::now()->diffInDays($nextDate, false);

                        if ($daysDiff < 0) {
                            return 'danger'; // Overdue
                        } elseif ($daysDiff <= 30) {
                            return 'warning'; // Due within 30 days
                        }

                        return 'info'; // Normal
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_ticket')
                    ->label('Buat Tiket Maintenance')
                    ->icon('heroicon-o-calendar')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Asset $record) => "Terbitkan Tiket Maintenance Berkala: {$record->asset_tag}?")
                    ->modalDescription('Sistem akan otomatis membuatkan Tiket IT Service kategori Scheduled Service dan menugaskannya ke Tim IT.')
                    ->action(function (Asset $record) {
                        $ticket = Ticket::create([
                            'ticket_number' => Ticket::generateTicketNumber(),
                            'reporter_name' => $record->holder_name !== '-' ? $record->holder_name : 'Tim IT Maintenance',
                            'reporter_department' => $record->department ?? 'IT Operations',
                            'contact_number' => 'Ext IT',
                            'location_id' => $record->location_id,
                            'room' => $record->room ?? 'Ruangan Pengerjaan',
                            'asset_id' => $record->id,
                            'asset_tag' => $record->asset_tag,
                            'asset_name' => "{$record->assetModel?->manufacturer} {$record->assetModel?->name}",
                            'category' => 'scheduled_service',
                            'subject' => "Preventive Maintenance Rutin Berkala - {$record->asset_tag}",
                            'description' => "Jadwal maintenance rutin berkala IT untuk unit {$record->asset_tag} ({$record->assetModel?->name}). Pengerjaan meliputi: Pembersihan fisik/debu, pengecekan komponen, backup data, dan update OS/antivirus.",
                            'scheduled_date' => now(),
                            'scheduled_time_slot' => '10:00 - 12:00',
                            'priority' => 'medium',
                            'status' => 'scheduled',
                            'assigned_to' => Auth::id() ?: 1,
                        ]);

                        Notification::make()
                            ->title("Tiket Maintenance {$ticket->ticket_number} Berhasil Diterbitkan")
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
