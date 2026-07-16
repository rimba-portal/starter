<?php

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Tree\Menu\Models\Menu;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Menu';

    protected ?string $subheading = 'Catalog of all company links.';

    public function getTabs(): array
    {
        $categories = Menu::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter() // remove nulls if needed
            ->toArray();

        $tabs = [];

        $tabs['all'] = Tab::make(); // default tab showing all records

        foreach ($categories as $category) {
            $tabs[$category] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category));
        }

        return $tabs;
    }
}
