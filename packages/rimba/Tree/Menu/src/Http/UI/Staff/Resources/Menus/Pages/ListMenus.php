<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Tree\Menu\Enums\MenuCategory;
use Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\MenuResource;
use Rimba\Tree\Menu\Models\Menu;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Menu';

    protected ?string $subheading = 'Catalog of all company links.';

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All'),
        ];

        foreach (MenuCategory::cases() as $category) {
            $tabs[$category->value] = Tab::make($category->label())
                ->icon($category->icon())
                ->modifyQueryUsing(fn ($query) => $query->where(
                    'category',
                    $category->value,
                ));
        }

        return $tabs;
    }

    // public function getTabs(): array
    // {
    //     $categories = Menu::query()
    //         ->select('category')
    //         ->distinct()
    //         ->pluck('category')
    //         ->filter() // remove nulls if needed
    //         ->toArray();

    //     $tabs = [];

    //     $tabs['all'] = Tab::make(); // default tab showing all records

    //     foreach ($categories as $category) {
    //         $tabs[$category] = Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category))
    //             ->icon('heroicon-m-user-group');
    //     }

    //     return $tabs;
    // }
}
