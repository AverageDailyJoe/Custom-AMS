<?php

namespace App\Filament\Resources\PengajuanAsetResource\Pages;

use App\Filament\Resources\PengajuanAsetResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePengajuanAset extends CreateRecord
{
    protected static string $resource = PengajuanAsetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return PengajuanAsetResource::syncLegacyColumns($data);
    }
}
