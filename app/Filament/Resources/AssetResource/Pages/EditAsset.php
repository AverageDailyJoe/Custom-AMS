<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            Actions\Action::make('upload_documents')
                ->label('Dokumen Fisik (Upload / View)')
                ->icon('heroicon-o-paper-clip')
                ->color('info')
                ->modalHeading('Upload & Dokumentasi Arsip Fisik Asset')
                ->modalSubmitActionLabel('Simpan Dokumen')
                ->modalCancelActionLabel('Tutup')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('attachments')
                        ->label('Dokumen Fisik (Invoice, Manual Book, Kartu Garansi, Bukti Fisik)')
                        ->directory('asset-documents')
                        ->multiple()
                        ->reorderable()
                        ->appendFiles()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                        ->maxSize(10240)
                        ->openable()
                        ->downloadable()
                        ->default(fn () => $this->getRecord()->attachments),
                ])
                ->action(function (array $data) {
                    $this->getRecord()->update([
                        'attachments' => $data['attachments'] ?? null,
                    ]);
                    $this->fillForm();

                    \Filament\Notifications\Notification::make()
                        ->title('Dokumen fisik berhasil disimpan!')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('print_sticker')
                ->label('Cetak Stiker Tag (121)')
                ->icon('heroicon-o-qr-code')
                ->color('warning')
                ->modalHeading('Pilih Posisi Slot Stiker (Tom & Jerry 121)')
                ->modalDescription('Pilih nomor slot (1 s/d 10) pada lembar label Tom & Jerry tempat stiker unit ini akan dicetak.')
                ->modalSubmitActionLabel('Buka Halaman Cetak')
                ->modalCancelActionLabel('Batal')
                ->form([
                    \Filament\Forms\Components\Select::make('slot')
                        ->label('Posisi Slot Stiker Pada Lembar Label')
                        ->options([
                            1 => 'Slot 1 (Baris 1 - Kiri)',
                            2 => 'Slot 2 (Baris 1 - Kanan)',
                            3 => 'Slot 3 (Baris 2 - Kiri)',
                            4 => 'Slot 4 (Baris 2 - Kanan)',
                            5 => 'Slot 5 (Baris 3 - Kiri)',
                            6 => 'Slot 6 (Baris 3 - Kanan)',
                            7 => 'Slot 7 (Baris 4 - Kiri)',
                            8 => 'Slot 8 (Baris 4 - Kanan)',
                            9 => 'Slot 9 (Baris 5 - Kiri)',
                            10 => 'Slot 10 (Baris 5 - Kanan)',
                        ])
                        ->default(1)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $url = route('assets.sticker-121', [
                        'asset' => $this->getRecord()->id,
                        'slot' => $data['slot'] ?? 1,
                    ]);
                    $this->js("window.open('{$url}', '_blank');");
                }),
        ];
    }


    protected function afterSave(): void
    {
        $record = $this->getRecord();
        if ($record->status === 'checked_out' || !empty($record->primary_user)) {
            if (!$record->currentCheckout()->exists()) {
                $record->checkouts()->create([
                    'primary_user' => $record->primary_user,
                    'secondary_user' => $record->secondary_user,
                    'checked_out_by' => auth()->id(),
                    'checked_out_at' => now(),
                    'checkout_notes' => 'Log dari data penguasaan asset',
                ]);
            }
        }
    }
}
