<?php

namespace App\Filament\Resources\PengajuanAsetResource\Pages;

use App\Filament\Resources\PengajuanAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengajuanAset extends EditRecord
{
    protected static string $resource = PengajuanAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf_ppb')
                ->label('Cetak PPB')
                ->icon('heroicon-o-document-text')
                ->color('warning')
                ->url(fn ($record) => route('pengajuan-asets.pdf-ppb', $record))
                ->openUrlInNewTab(),
            Actions\Action::make('pdf_lbs')
                ->label('Cetak LBS')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('info')
                ->url(fn ($record) => route('pengajuan-asets.pdf-lbs', $record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
