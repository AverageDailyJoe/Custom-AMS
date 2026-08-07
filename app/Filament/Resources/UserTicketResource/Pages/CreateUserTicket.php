<?php

namespace App\Filament\Resources\UserTicketResource\Pages;

use App\Filament\Resources\UserTicketResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUserTicket extends CreateRecord
{
    protected static string $resource = UserTicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'open';
        $data['created_by'] = Auth::id();

        return $data;
    }
}
