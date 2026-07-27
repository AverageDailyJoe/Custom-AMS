<?php

namespace App\Filament\Resources\DisposeAsetResource\Pages;

use App\Filament\Resources\DisposeAsetResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDisposeAset extends CreateRecord
{
    protected static string $resource = DisposeAsetResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();
        return $data;
    }
}
