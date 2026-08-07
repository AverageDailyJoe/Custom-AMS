<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserTicketResource\Pages;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserTicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationLabel = 'IT Service (User)';

    protected static ?string $modelLabel = 'Tiket Pelaporan User';

    protected static ?string $pluralModelLabel = 'IT Service (User)';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas Pelapor & Lokasi Pengerjaan')
                ->description('Tuliskan data diri dan lokasi keberadaan perangkat yang memerlukan perbaikan')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('ticket_number')
                            ->label('No. Tiket')
                            ->default(fn () => Ticket::generateTicketNumber())
                            ->required()
                            ->readOnly()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('reporter_name')
                            ->label('Nama Karyawan / Pelapor')
                            ->default(fn () => Auth::user()?->name)
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
                            ->label('Ruangan / Area Kerja')
                            ->placeholder('Contoh: RUANG HCD, GUDANG FACTORY'),
                    ]),

                    Forms\Components\TextInput::make('room_notes')
                        ->label('Catatan Lokasi Sementara (Jika Sedang Rapat / Berada di Tempat Lain)')
                        ->placeholder('Misal: Rapat di Ruang Direksi Lt 2 / Sedang di Pabrik Cikarang Line 3')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Informasi Perangkat Bermasalah (Ketik Manual)')
                ->description('Ketikkan informasi perangkat atau barang yang bermasalah. Staff IT akan menghubungkannya ke sistem inventaris AMS.')
                ->schema([
                    Forms\Components\TextInput::make('asset_name')
                        ->label('Perangkat / Model / Serial Number (Ketik Manual)')
                        ->placeholder('Contoh: Laptop ThinkPad X1 Carbon Hitam / Monitor Dell 24 Inch / Printer Epson L3110')
                        ->helperText('Sebutkan merk, jenis unit, atau serial number perangkat jika Anda mengetahuinya.')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Detail Kendala & Bukti Foto')
                ->description('Jelaskan masalah yang dialami serta sertakan tangkapan layar/foto jika ada')
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
                            ->label('Prioritas Kendala')
                            ->options([
                                'low' => 'Low (Rendah / SLA 3 Hari Kerja)',
                                'medium' => 'Medium (Sedang / SLA 2 Hari Kerja)',
                                'high' => 'High (Tinggi / SLA 1 Hari Kerja)',
                                'critical' => 'Critical / Emergency (SLA Hari Ini)',
                            ])
                            ->default('medium')
                            ->required(),
                    ]),

                    Forms\Components\TextInput::make('subject')
                        ->label('Judul Kendala / Permintaan')
                        ->placeholder('Contoh: Laptop mati total, Printer macet, Ganti SSD')
                        ->required(),

                    Forms\Components\Textarea::make('description')
                        ->label('Detail Kendala & Penjelasan')
                        ->placeholder('Jelaskan detail gejala masalah yang dialami...')
                        ->rows(3)
                        ->required(),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Upload Foto / Tangkapan Layar Kendala (User)')
                        ->disk('public')
                        ->directory('ticket-attachments')
                        ->visibility('public')
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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Pelaporan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reporter_name')
                    ->label('Pelapor')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Judul Kendala')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Perangkat (Manual)')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('assignedToUser.name')
                    ->label('Teknisi IT')
                    ->placeholder('Belum Ditugaskan'),

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
                        'open' => 'Open (Tiket Baru)',
                        'scheduled' => 'Terjadwal IT',
                        'in_progress' => 'In Progress',
                        'pending_sparepart' => 'Menunggu Sparepart',
                        'rescheduled' => 'Jadwal Diubah',
                        'resolved' => 'Selesai (Resolved)',
                        'closed' => 'Closed',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('pdf_ticket')
                    ->label('Cetak Tiket')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('tickets.pdf', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make()
                    ->label('Lihat / Detail'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserTickets::route('/'),
            'create' => Pages\CreateUserTicket::route('/create'),
            'edit' => Pages\EditUserTicket::route('/{record}/edit'),
        ];
    }
}
