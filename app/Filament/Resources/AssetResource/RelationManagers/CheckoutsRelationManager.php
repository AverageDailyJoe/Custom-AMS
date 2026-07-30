<?php

namespace App\Filament\Resources\AssetResource\RelationManagers;

use App\Models\Checkout;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CheckoutsRelationManager extends RelationManager
{
    protected static string $relationship = 'checkouts';

    protected static ?string $title = 'Riwayat Checkout & Pengecekan Pengembalian';

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
                Tables\Columns\TextColumn::make('checked_out_at')->label('Tanggal Checkout')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('checked_in_at')->label('Tanggal Checkin')->dateTime('d M Y H:i')->placeholder('Sedang Digunakan'),
                Tables\Columns\TextColumn::make('checkedOutByUser.name')
                    ->label('Petugas (Admin)')
                    ->getStateUsing(fn ($record) => $record->checkedOutByUser?->name ?? $record->checkedInByUser?->name ?? 'Admin'),
                Tables\Columns\TextColumn::make('checklist_summary')
                    ->label('Pengecekan Pengembalian')
                    ->getStateUsing(function ($record) {
                        if (!$record->checked_in_at) {
                            return '-';
                        }
                        $chk = $record->component_checklist ?? [];
                        $damaged = 0;
                        foreach (['layar', 'keyboard', 'ram', 'ssd', 'trackpad', 'baterai', 'hardware', 'charger'] as $k) {
                            if (($chk[$k . '_status'] ?? 'baik') === 'rusak') {
                                $damaged++;
                            }
                        }
                        return $damaged > 0 ? "⚠️ {$damaged} Komponen Rusak" : '✓ 8 Komponen Baik';
                    })
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->checked_in_at) return 'gray';
                        $chk = $record->component_checklist ?? [];
                        foreach (['layar', 'keyboard', 'ram', 'ssd', 'trackpad', 'baterai', 'hardware', 'charger'] as $k) {
                            if (($chk[$k . '_status'] ?? 'baik') === 'rusak') {
                                return 'danger';
                            }
                        }
                        return 'success';
                    }),
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
                Tables\Columns\TextColumn::make('checkout_notes')->label('Catatan')->limit(30),
            ])
            ->defaultSort('checked_out_at', 'desc')
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('view_checklist')
                    ->label('Rincian Pengecekan')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->checked_in_at !== null)
                    ->modalHeading(fn ($record) => "Rincian Pengecekan Kondisi Fisik ({$record->holder_name})")
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(fn ($record) => view('filament.components.checklist-modal', ['checklist' => $record->component_checklist ?? []])),

                Tables\Actions\Action::make('edit_checklist')
                    ->label('Edit Tabel Pengecekan')
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->visible(fn ($record) => $record->checked_in_at !== null)
                    ->form([
                        Forms\Components\Section::make('Custom Keterangan & Kondisi Komponen saat Pengembalian')
                            ->description('Tuliskan atau sesuaikan keterangan custom untuk masing-masing komponen saat pengembalian laptop.')
                            ->schema([
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.layar_status')->label('1. Layar / Display')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.layar_notes')->label('Keterangan Layar')->placeholder('Misal: Normal / Ada bintik putih / Gores halus')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.keyboard_status')->label('2. Keyboard')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.keyboard_notes')->label('Keterangan Keyboard')->placeholder('Misal: Normal / Tombol Enter agak keras')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.ram_status')->label('3. RAM / Memory')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.ram_notes')->label('Keterangan RAM')->placeholder('Misal: 8 GB DDR4 Normal')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.ssd_status')->label('4. SSD / Storage')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.ssd_notes')->label('Keterangan Storage')->placeholder('Misal: SSD 256 GB Normal')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.trackpad_status')->label('5. Trackpad / Mouse')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.trackpad_notes')->label('Keterangan Trackpad')->placeholder('Misal: Normal / Mouse wireless termasuk')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.baterai_status')->label('6. Baterai')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.baterai_notes')->label('Keterangan Baterai')->placeholder('Misal: Health 85% / Tahan 3 jam')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.hardware_status')->label('7. Hardware & CPU')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.hardware_notes')->label('Keterangan Hardware')->placeholder('Misal: Intel i5 Normal')->columnSpan(2),
                                ]),
                                Forms\Components\Grid::make(3)->schema([
                                    Forms\Components\Select::make('component_checklist.charger_status')->label('8. Charger / Power')->options(['baik' => 'Baik', 'rusak' => 'Rusak'])->required(),
                                    Forms\Components\TextInput::make('component_checklist.charger_notes')->label('Keterangan Charger')->placeholder('Misal: Lengkap dengan kabel power')->columnSpan(2),
                                ]),
                            ]),
                    ])
                    ->fillForm(fn ($record) => [
                        'component_checklist' => array_merge([
                            'layar_status' => 'baik', 'layar_notes' => 'Normal',
                            'keyboard_status' => 'baik', 'keyboard_notes' => 'Normal',
                            'ram_status' => 'baik', 'ram_notes' => 'Normal',
                            'ssd_status' => 'baik', 'ssd_notes' => 'Normal',
                            'trackpad_status' => 'baik', 'trackpad_notes' => 'Normal',
                            'baterai_status' => 'baik', 'baterai_notes' => 'Berfungsi baik',
                            'hardware_status' => 'baik', 'hardware_notes' => 'Normal',
                            'charger_status' => 'baik', 'charger_notes' => 'Lengkap dengan kabel power',
                        ], $record->component_checklist ?? []),
                    ])
                    ->action(function (Checkout $record, array $data) {
                        $record->update([
                            'component_checklist' => $data['component_checklist'],
                        ]);

                        Notification::make()
                            ->title("Keterangan tabel pengecekan pengembalian berhasil diperbarui")
                            ->success()
                            ->send();
                    }),

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
