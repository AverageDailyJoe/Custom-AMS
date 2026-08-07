<?php

namespace App\Filament\Resources\UserTicketResource\Pages;

use App\Filament\Resources\UserTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserTicket extends EditRecord
{
    protected static string $resource = UserTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
