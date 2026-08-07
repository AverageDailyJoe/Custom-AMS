<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class UserHelpdeskInfoWidget extends Widget
{
    protected static string $view = 'filament.widgets.user-helpdesk-info';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return \Filament\Facades\Filament::getCurrentPanel()?->getId() === 'user';
    }
}
