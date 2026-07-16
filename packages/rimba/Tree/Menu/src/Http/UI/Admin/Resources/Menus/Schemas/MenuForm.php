<?php

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category')
                    ->required(),
                TextInput::make('group'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('icon'),
                TextInput::make('color'),
                TextInput::make('parent_id')
                    ->numeric(),
                TextInput::make('permission'),
                TextInput::make('panel'),
                Toggle::make('is_visible')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
