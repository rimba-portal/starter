<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events;

use BackedEnum;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\CreateEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\EditEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\ListEvents;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\ViewEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas\EventForm;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas\EventInfolist;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Tables\EventsTable;
use Bites\Calendar\Models\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|UnitEnum|null $navigationGroup = 'Calendar';

    protected static string|BackedEnum|null $navigationIcon = 'bites-event';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'view' => ViewEvent::route('/{record}'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
