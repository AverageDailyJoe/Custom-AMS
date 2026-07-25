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
            Actions\Action::make('pdf_pengajuan')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn ($record) => route('pengajuan-asets.pdf', $record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
