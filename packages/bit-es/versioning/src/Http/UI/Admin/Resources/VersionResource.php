<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions;

use Bites\Versioning\Models\Version;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class VersionResource extends Resource
{
    protected static ?string $model =
        Version::class;

    public static function form(
        Form $form
    ): Form {
        return $form->schema([

            TextInput::make('version')
                ->required(),

            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'review' => 'Review',
                    'approved' => 'Approved',
                    'released' => 'Released',
                    'obsolete' => 'Obsolete',
                    'archived' => 'Archived',
                ]),

            TextInput::make('content_url')
                ->url(),

            DateTimePicker::make(
                'effective_from'
            ),

            DateTimePicker::make(
                'effective_until'
            ),

            MarkdownEditor::make('notes'),
        ]);
    }
}
