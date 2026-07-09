<?php

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use App\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'person' => Tab::make('Person')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('family', 'person')),
            'thing' => Tab::make('Thing')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('family', 'thing')),
            'location' => Tab::make('Location')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('family', 'location')),
        ];
    }
}
