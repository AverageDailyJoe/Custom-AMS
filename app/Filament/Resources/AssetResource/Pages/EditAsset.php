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
