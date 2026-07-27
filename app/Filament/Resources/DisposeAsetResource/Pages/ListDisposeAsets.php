<?php

namespace App\Filament\Resources\DisposeAsetResource\Pages;

use App\Filament\Resources\DisposeAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisposeAsets extends ListRecords
{
    protected static string $resource = DisposeAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Disposal Baru'),
        ];
    }
}
