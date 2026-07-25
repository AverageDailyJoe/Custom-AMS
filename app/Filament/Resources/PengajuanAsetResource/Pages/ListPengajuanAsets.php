<?php

namespace App\Filament\Resources\PengajuanAsetResource\Pages;

use App\Filament\Resources\PengajuanAsetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengajuanAsets extends ListRecords
{
    protected static string $resource = PengajuanAsetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Pengajuan Baru'),
        ];
    }
}
