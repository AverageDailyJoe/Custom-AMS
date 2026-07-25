<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaAcaraResource\Pages;
use App\Models\Asset;
use App\Models\BeritaAcara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class BeritaAcaraResource extends Resource
{
    protected static ?string $model = BeritaAcara::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $navigationLabel = 'Berita Acara IT';

    protected static ?string $modelLabel = 'Berita Acara IT';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Nomor & Jenis Berita Acara')
                ->schema([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('letter_number')
                            ->label('Nomor Berita Acara')
                            ->default(fn () => BeritaAcara::generateLetterNumber())
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('letter_date')
                            ->label('Tanggal')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('category')
                            ->label('Kategori Berita Acara')
                            ->options([
                                'kehilangan' => '1. Kehilangan Asset IT',
                                'kerusakan_sparepart' => '2. Kerusakan / Perbaikan / Spare Part',
                                'transfer_asset' => '3. Mutasi / Perpindahan Tangan Asset',
                                'penggantian_unit' => '4. Penggantian Unit Asset Baru/Pengganti',
                            ])
                            ->default('kehilangan')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                if ($state === 'kehilangan') {
                                    $set('title', 'Berita Acara Kehilangan Asset IT');
                                    $set('description_points', "1. Bahwa PIHAK KEDUA telah mengalami kehilangan 1 (satu) unit laptop/perangkat pribadi yang digunakan untuk mendukung pekerjaan sehari-hari.\n2. Bahwa sebagai bentuk kepedulian dan empati perusahaan atas kejadian tersebut, perusahaan memutuskan untuk memberikan 1 (satu) unit laptop pengganti terhadap PIHAK KEDUA.\n3. Bahwa pemberian laptop pengganti ini bersifat bantuan dari perusahaan dan bukan merupakan kewajiban ganti rugi atas kehilangan tersebut.\n4. Bahwa PIHAK PERTAMA selaku perwakilan perusahaan telah menyerahkan unit laptop pengganti kepada PIHAK KEDUA, dan PIHAK KEDUA telah menerima unit tersebut.");
                                } elseif ($state === 'kerusakan_sparepart') {
                                    $set('title', 'Berita Acara Perbaikan / Spare Part Asset IT');
                                    $set('description_points', "1. Bahwa PIHAK KEDUA melaporkan adanya indikasi kerusakan/penurunan performa pada unit aset IT.\n2. Bahwa PIHAK PERTAMA telah melakukan pemeriksaan teknis dan mengkonfirmasi perbaikan/penggantian komponen spare part.\n3. Bahwa unit aset telah selesai diperbaiki dan siap digunakan kembali untuk mendukung operasional perusahaan.");
                                } elseif ($state === 'transfer_asset') {
                                    $set('title', 'Berita Acara Mutasi / Transfer Asset IT');
                                    $set('description_points', "1. Bahwa PIHAK PERTAMA telah menyerahkan unit aset IT dari lokasi/divisi asal kepada PIHAK KEDUA.\n2. Bahwa PIHAK KEDUA telah menerima unit tersebut dan bertanggung jawab atas penggunaan unit di lokasi/departemen baru.\n3. Bahwa seluruh data dan hak akses telah disesuaikan sesuai prosedur IT perusahaan.");
                                } elseif ($state === 'penggantian_unit') {
                                    $set('title', 'Berita Acara Penyerahan Unit Asset Baru/Pengganti');
                                    $set('description_points', "1. Bahwa dilakukan penyerahan unit pengganti/baru untuk mendukung pekerjaan PIHAK KEDUA.\n2. Bahwa PIHAK KEDUA telah menerima unit pengganti dalam kondisi baik dan siap pakai.\n3. Bahwa unit lama telah dikembalikan penuh ke pihak IT.");
                                }
                            }),
                    ]),

                    Forms\Components\TextInput::make('title')
                        ->label('Judul / Hal Berita Acara')
                        ->default('Berita Acara IT')
                        ->placeholder('Misal: Berita Acara Perbaikan Laptop')
                        ->columnSpanFull(),

                    Forms\Components\Select::make('asset_id')
                        ->label('Pilih Unit Asset IT')
                        ->relationship('asset', 'asset_tag')
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Set $set, ?string $state) {
                            if ($state) {
                                $asset = Asset::find($state);
                                if ($asset) {
                                    $set('asset_tag', $asset->asset_tag);
                                    $set('asset_name', "{$asset->assetModel?->manufacturer} {$asset->assetModel?->name}");
                                }
                            }
                        })
                        ->columnSpanFull(),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('asset_tag')
                            ->label('ID Inventaris (Asset Tag)')
                            ->required(),

                        Forms\Components\TextInput::make('asset_name')
                            ->label('Nama / Model Barang')
                            ->required(),
                    ]),
                ]),

            Forms\Components\Section::make('Pihak Yang Bertandatangan')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Fieldset::make('PIHAK PERTAMA (Yang Menyerahkan / IT)')
                            ->schema([
                                Forms\Components\TextInput::make('party1_name')
                                    ->label('Nama Pihak 1')
                                    ->default(fn () => Auth::user()?->name ?? 'ADITYAR INDRA PANGESTU')
                                    ->required(),
                                Forms\Components\TextInput::make('party1_title')
                                    ->label('Jabatan Pihak 1')
                                    ->default('IT STAFF')
                                    ->required(),
                                Forms\Components\TextInput::make('party1_department')
                                    ->label('Departemen Pihak 1')
                                    ->default('INFORMATION & TECHNOLOGY')
                                    ->required(),
                            ]),

                        Forms\Components\Fieldset::make('PIHAK KEDUA (Yang Menerima / Pengguna)')
                            ->schema([
                                Forms\Components\TextInput::make('party2_name')
                                    ->label('Nama Pihak 2')
                                    ->required(),
                                Forms\Components\TextInput::make('party2_title')
                                    ->label('Jabatan Pihak 2')
                                    ->placeholder('Misal: MT Digital Commerce'),
                                Forms\Components\TextInput::make('party2_department')
                                    ->label('Departemen Pihak 2')
                                    ->placeholder('Misal: Digital Marketing'),
                            ]),
                    ]),

                    Forms\Components\Fieldset::make('MENYETUJUI (Atasan / Manager)')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('approver_name')
                                    ->label('Nama Atasan')
                                    ->default('SETYADI CANDRAWINATA'),
                                Forms\Components\TextInput::make('approver_title')
                                    ->label('Jabatan Atasan')
                                    ->default('GM Finance & Operations'),
                            ]),
                        ]),
                ]),

            Forms\Components\Section::make('Detail Unit & Isi Berita Acara')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('quantity')
                            ->label('Jumlah')
                            ->default('1 Unit')
                            ->required(),

                        Forms\Components\TextInput::make('completeness')
                            ->label('Kelengkapan')
                            ->default('1 Unit Laptop + Charger')
                            ->required(),
                    ]),

                    Forms\Components\Textarea::make('description_points')
                        ->label('Poin-Poin Menerangkan Berita Acara')
                        ->rows(6)
                        ->default("1. Bahwa PIHAK KEDUA telah mengalami kehilangan 1 (satu) unit laptop/perangkat pribadi yang digunakan untuk mendukung pekerjaan sehari-hari di lingkungan perusahaan.\n2. Bahwa sebagai bentuk kepedulian dan empati perusahaan atas kejadian tersebut, perusahaan memutuskan untuk memberikan 1 (satu) unit laptop pengganti terhadap PIHAK KEDUA.\n3. Bahwa pemberian laptop pengganti ini bersifat bantuan dari perusahaan dan bukan merupakan kewajiban ganti rugi atas kehilangan tersebut.\n4. Bahwa PIHAK PERTAMA selaku perwakilan perusahaan telah menyerahkan unit laptop pengganti kepada PIHAK KEDUA, dan PIHAK KEDUA telah menerima unit tersebut.")
                        ->required(),

                    Forms\Components\FileUpload::make('attachments')
                        ->label('Dokumen / Lampiran Pendukung')
                        ->directory('berita-acara-attachments')
                        ->multiple()
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
                Tables\Columns\TextColumn::make('letter_number')
                    ->label('No. Berita Acara')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('letter_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'kehilangan' => 'danger',
                        'kerusakan_sparepart' => 'warning',
                        'transfer_asset' => 'info',
                        'penggantian_unit' => 'success',
                    })
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'kehilangan' => 'Kehilangan',
                        'kerusakan_sparepart' => 'Perbaikan/Sparepart',
                        'transfer_asset' => 'Mutasi/Transfer',
                        'penggantian_unit' => 'Penggantian Unit',
                    }),

                Tables\Columns\TextColumn::make('asset_tag')
                    ->label('ID Inventaris')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asset_name')
                    ->label('Nama Unit')
                    ->searchable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('party2_name')
                    ->label('Penerima (Pihak 2)')
                    ->searchable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('pdf_berita_acara')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('berita-acaras.pdf', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritaAcaras::route('/'),
            'create' => Pages\CreateBeritaAcara::route('/create'),
            'edit' => Pages\EditBeritaAcara::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(['asset']);
    }
}
