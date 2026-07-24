<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAsset extends CreateRecord
{
    protected static string $resource = AssetResource::class;

    protected function afterCreate(): void
    {
        $record = $this->getRecord();
        if ($record->status === 'checked_out' || !empty($record->primary_user)) {
            $record->checkouts()->create([
                'primary_user' => $record->primary_user,
                'secondary_user' => $record->secondary_user,
                'checked_out_by' => auth()->id(),
                'checked_out_at' => now(),
                'checkout_notes' => 'Log dari pembuatan asset awal',
            ]);
        }
    }
}
