<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name')
                    ->required(),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                Select::make('quiz_attempt_id')
                    ->relationship('quizAttempt', 'id'),
                Select::make('evaluation_id')
                    ->relationship('evaluation', 'id'),
                TextInput::make('issued_by')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
                DateTimePicker::make('issued_at'),
                DateTimePicker::make('expires_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
