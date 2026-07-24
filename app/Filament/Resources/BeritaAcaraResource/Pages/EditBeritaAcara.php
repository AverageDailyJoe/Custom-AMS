<?php

namespace App\Filament\Resources\BeritaAcaraResource\Pages;

use App\Filament\Resources\BeritaAcaraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBeritaAcara extends EditRecord
{
    protected static string $resource = BeritaAcaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf_berita_acara')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn ($record) => route('berita-acaras.pdf', $record))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }
}
