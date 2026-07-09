<?php

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use App\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListThingAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;
    
    protected string $family = 'thing';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(
                    fn(Builder $query) => $query->where('family', $this->family)
                ),
            ...$this->getGroupTabs(),
        ];
    }

    protected function getGroupTabs(): array
    {
        $tabs = [];

        foreach (config('bites.groups.' . $this->family, []) as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(
                    fn(Builder $query) => $query
                        ->where('family', $this->family)
                        ->where('group', $key)
                );
        }

        return $tabs;
    }
}
