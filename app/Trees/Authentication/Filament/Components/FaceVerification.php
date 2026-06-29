<?php

declare(strict_types=1);

namespace App\Trees\Authentication\Filament\Components;

use Filament\Schemas\Components\Component;
use Filament\Forms\Components\Field;

class FaceVerification extends Field
{
    protected string $view = 'filament.components.face-verification';
}