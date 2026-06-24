<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkflowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('key')->required()->unique(ignorable: fn ($record) => $record),
            ]);
    }
}
