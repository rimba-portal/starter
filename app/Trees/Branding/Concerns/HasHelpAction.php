<?php

declare(strict_types=1);

namespace App\Trees\Branding\Concerns;

use App\Trees\Branding\Actions\GetHelpAction;

trait HasHelpAction
{
    protected function getHeaderActions(): array
    {
        return [

            ...parent::getHeaderActions(),
            GetHelpAction::make(),
        ];
    }
}
