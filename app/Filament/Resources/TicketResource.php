<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Asset;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'IT Service Tickets';

    protected static ?string $modelLabel = 'IT Ticket';

    protected static ?string $pluralModelLabel = 'IT Service Tickets';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Pelapor & Lokasi Pengerjaan')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('No. Tiket')
                            ->default(fn () => Ticket::generateTicketNumber())
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('reporter_name')
                            ->label('Nama Karyawan / Pelapor')
                            ->placeholder('Contoh: Bambang Yulianto')
                            ->required(),

                        Forms\Components\TextInput::make('reporter_department')
                            ->label('Departemen Pelapor')
                            ->placeholder('Contoh: Marketing / Finance / Factory')
                            ->required(),
                    ]),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('contact_number')
                            ->label('No. HP / WA / Ext')
                            ->placeholder('Contoh: 08123456789 / Ext 104'),

                        Forms\Components\Select::make('location_id')
                            ->label('Lokasi Utama')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('room')
                            ->label('Ruangan Utama (Dari Asset)')
                            ->placeholder('Contoh: RUANG HCD, GUDANG FACTORY'),
                    ]),

                    Forms\Components\TextInput::make('room_notes')
                        ->label('Catatan Lokasi Sementara / Pengerjaan Khusus (Jika Sedang Rapat / Berada di Tempat Lain)')
                        ->placeholder('Misal: User sedang rapat di Ruang Direksi Lt 2 / Sedang di Pabrik Cikarang Line 3')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Perangkat / Asset IT Yang Bermasalah (AMS)')
                ->description('Pilih unit aset yang dilaporkan jika kendala terkait perangkat inventaris terdaftar')
                ->schema([
                    Forms\Components\Select::make('asset_id')
                        ->label('Pilih Unit Asset IT (Cari Tag / Serial / Model / User)')
                        ->placeholder('Ketik ID Inventaris (Contoh: GTK-02-03-21), Model, atau Nama User...')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search): array {
                            return Asset::query()
                                ->with(['assetModel', 'location'])
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
                                    $user = $asset->holder_name !== '-' ? " (User: {$asset->holder_name})" : '';
                                    $model = $asset->assetModel ? " - {$asset->assetModel->manufacturer} {$asset->assetModel->name}" : '';
                                    $statusBadge = " [{$asset->status}]";
                                    return [$asset->id => "{$asset->asset_tag}{$model}{$user}{$statusBadge}"];
                                })
                                ->toArray();
                        })
                        ->getOptionLabelUsing(function ($value): ?string {
                            $asset = Asset::with(['assetModel', 'location'])->find($value);
                            if (!$asset) return null;
                            $user = $asset->holder_name !== '-' ? " (User: {$asset->holder_name})" : '';
                            $model = $asset->assetModel ? " - {$asset->assetModel->manufacturer} {$asset->assetModel->name}" : '';
                            $statusBadge = " [{$asset->status}]";
                            return "{$asset->asset_tag}{$model}{$user}{$statusBadge}";
                        })
                        ->live()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $asset = Asset::with(['assetModel', 'location'])->find($state);
                                if ($asset) {
                                    $set('asset_tag', $asset->asset_tag);
                                    $set('asset_name', "{$asset->assetModel?->manufacturer} {$asset->assetModel?->name}");
                                    
                                    // Smart Auto-Fill Data Pelapor & Lokasi
                                    if ($asset->holder_name !== '-') {
                                        $set('reporter_name', $asset->holder_name);
                                    }
                                    if (!empty($asset->department)) {
                                        $set('reporter_department', $asset->department);
                                    }
                                    if (!empty($asset->location_id)) {
                                        $set('location_id', $asset->location_id);
                                    }
                                    if (!empty($asset->room)) {
                                        $set('room', $asset->room);
                                    }
                                }
                            }
                        })
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('asset_tag')
                            ->label('ID Inventaris / Tag'),

                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama / Model Unit'),
                    ]),
                ]),

            Forms\Components\Section::make('Detail Kendala & Penjadwalan IT (Schedule-Driven)')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('category')
                            ->label('Kategori Layanan / Kendala')
                            ->options([
                                'hardware' => 'Hardware / Perangkat Fisik',
                                'software' => 'Software / Aplikasi / OS',
                                'network_wifi' => 'Network / Internet / Wi-Fi',
                                'printer_scanner' => 'Printer / Scanner / PERIPHERAL',
                                'access_rights' => 'Hak Akses / Email / Reset Password',
                                'scheduled_service' => 'Maintenance Rutin Berjadwal',
                                'other' => 'Lain-lain',
                            ])
                            ->default('hardware')
                            ->required(),

                        Forms\Components\Select::make('priority')
                            ->label('Prioritas (SLA)')
                            ->options([
                                'low' => 'Low (Rendah / SLA 3 Hari Kerja)',
                                'medium' => 'Medium (Sedang / SLA 2 Hari Kerja)',
                                'high' => 'High (Tinggi / SLA 1 Hari Kerja)',
                                'critical' => 'Critical / Emergency (SLA Hari Ini)',
                            ])
                            ->default('medium')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                $schedDate = $get('scheduled_date') ? \Carbon\Carbon::parse($get('scheduled_date')) : \Carbon\Carbon::now();
                                $days = match ($state) {
                                    'critical' => 0,
                                    'high' => 1,
                                    'medium' => 2,
                                    'low' => 3,
                                    default => 2,
                                };
                                $dueDate = $schedDate->copy();
                                while ($days > 0) {
                                    $dueDate->addDay();
                                    if (!$dueDate->isWeekend()) {
                                        $days--;
                                    }
                                }
                                $set('due_date', $dueDate->format('Y-m-d'));
                            }),
                    ]),

                    Forms\Components\TextInput::make('subject')
                        ->label('Judul Kendala / Permintaan')
                        ->placeholder('Contoh: Laptop mati total, Printer macet, Ganti SSD')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->label('Detail Kendala & Penjelasan')
                        ->placeholder('Jelaskan detail gejala masalah atau permintaan pengerjaan...')
                        ->rows(3)
                        ->required(),

                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->label('Tanggal Rencana Pengerjaan IT')
                            ->default(now())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?string $state) {
                                if ($state) {
                                    $priority = $get('priority') ?? 'medium';
                                    $schedDate = \Carbon\Carbon::parse($state);
                                    $days = match ($priority) {
                                        'critical' => 0,
                                        'high' => 1,
                                        'medium' => 2,
                                        'low' => 3,
                                        default => 2,
                                    };
                                    $dueDate = $schedDate->copy();
                                    while ($days > 0) {
                                        $dueDate->addDay();
                                        if (!$dueDate->isWeekend()) {
                                            $days--;
                                        }
                                    }
                                    $set('due_date', $dueDate->format('Y-m-d'));
                                }
                            }),

                        Forms\Components\Select::make('scheduled_time_slot')
                            ->label('Waktu / Jam Pengerjaan')
                            ->options([
                                '08:00 - 10:00' => 'Pagi (08:00 - 10:00)',
                                '10:00 - 12:00' => 'Pagi (10:00 - 12:00)',
                                '13:00 - 15:00' => 'Siang (13:00 - 15:00)',
                                '15:00 - 17:00' => 'Sore (15:00 - 17:00)',
                                'flexible' => 'Fleksibel Seharian',
                            ])
                            ->default('10:00 - 12:00')
                            ->required(),

                        Forms\Components\DatePicker::make('due_date')
                            ->label('Target Selesai / SLA')
                            ->default(now()->addDays(2)),
                    ]),

                    Forms\Components\Select::make('assigned_to')
                        ->label('Petugas IT Penanggung Jawab')
                        ->relationship('assignedToUser', 'name')
                        ->default(fn () => Auth::id())
                        ->searchable()
                        ->preload(),
                ]),

            Forms\Components\Section::make('Status Pengerjaan & Solusi IT')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Tiket')
                            ->options([
                                'open' => 'Open (Tiket Baru)',
                                'scheduled' => 'Scheduled (Terjadwal)',
                                'in_progress' => 'In Progress (Sedang Dikerjakan)',
                                'pending_sparepart' => 'Pending Sparepart (Menunggu PPB/LBS)',
                                'rescheduled' => 'Rescheduled (Jadwal Diubah)',
                                'resolved' => 'Resolved (Selesai Dikerjakan IT)',
                                'closed' => 'Closed (Ditutup)',
                            ])
                            ->default('scheduled')
                            ->required(),

                        Forms\Components\DateTimePicker::make('resolved_at')
                            ->label('Waktu Selesai (Resolved At)'),
                    ]),

                    Forms\Components\Textarea::make('reschedule_reason')
                        ->label('Alasan Perubahan / Penundaan Jadwal (Jika Rescheduled)')
                        ->placeholder('Misal: User sedang rapat / Menunggu barang pengadaan tiba')
                        ->rows(2),

                    Forms\Components\Textarea::make('resolution_notes')
                        ->label('Catatan Hasil Pengerjaan IT / Solusi')
                        ->placeholder('Tuliskan langkah perbaikan yang telah dilakukan...')
                        ->rows(3),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Upload Foto / Tangkapan Layar Kendala')
                        ->directory('ticket-attachments')
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
                Tables\Columns\TextColumn::make('ticket_number')
                    ->label('No. Tiket')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Tgl Jadwal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scheduled_time_slot')
                    ->label('Waktu')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->label('Target Selesai (SLA)')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Pelapor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Kendala')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('Asset Tag')
                    ->searchable()
                    ->placeholder('-'),

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
            ->defaultSort('scheduled_date', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'scheduled' => 'Terjadwal',
                        'in_progress' => 'In Progress',
                        'pending_sparepart' => 'Pending Part',
                        'rescheduled' => 'Rescheduled',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'hardware' => 'Hardware',
                        'software' => 'Software',
                        'network_wifi' => 'Network',
                        'printer_scanner' => 'Printer/Scanner',
                        'access_rights' => 'Hak Akses',
                        'scheduled_service' => 'Scheduled Service',
                        'other' => 'Lain-lain',
                    ]),

                Tables\Filters\Filter::make('scheduled_today')
                    ->label('Jadwal Hari Ini')
                    ->query(fn ($query) => $query->whereDate('scheduled_date', now())),
            ])
            ->actions([
                Tables\Actions\Action::make('reschedule')
                    ->label('Ubah Jadwal')
                    ->icon('heroicon-o-calendar')
                    ->color('warning')
                    ->form([
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->label('Tanggal Pengerjaan Baru')
                            ->default(now())
                            ->required(),
                        Forms\Components\Select::make('scheduled_time_slot')
                            ->label('Waktu / Jam Pengerjaan')
                            ->options([
                                '08:00 - 10:00' => 'Pagi (08:00 - 10:00)',
                                '10:00 - 12:00' => 'Pagi (10:00 - 12:00)',
                                '13:00 - 15:00' => 'Siang (13:00 - 15:00)',
                                '15:00 - 17:00' => 'Sore (15:00 - 17:00)',
                                'flexible' => 'Fleksibel Seharian',
                            ])
                            ->default('10:00 - 12:00')
                            ->required(),
                        Forms\Components\Textarea::make('reschedule_reason')
                            ->label('Alasan Perubahan Jadwal')
                            ->required(),
                    ])
                    ->action(function (Ticket $record, array $data) {
                        $record->update([
                            'scheduled_date' => $data['scheduled_date'],
                            'scheduled_time_slot' => $data['scheduled_time_slot'],
                            'reschedule_reason' => $data['reschedule_reason'],
                            'status' => 'rescheduled',
                        ]);

                        Notification::make()
                            ->title("Jadwal Tiket {$record->ticket_number} berhasil diperbarui")
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak Work Order')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['location', 'asset', 'assignedToUser']);
    }
}
