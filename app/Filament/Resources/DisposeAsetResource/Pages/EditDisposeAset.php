<?php

namespace App\Filament\Resources\DisposeAsetResource\Pages;

use App\Filament\Resources\DisposeAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisposeAset extends EditRecord
{
    protected static string $resource = DisposeAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf_disposal')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn ($record) => route('dispose-asets.pdf', $record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
