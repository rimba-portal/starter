<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
