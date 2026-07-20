<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name'),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                Select::make('evaluator_id')
                    ->relationship('evaluator', 'name'),
                TextInput::make('result'),
                DateTimePicker::make('evaluated_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
