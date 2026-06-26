<?php

declare(strict_types=1);

namespace App\Http\UI\Staff\Pages;

use App\Trees\Calendar\Support\ShiftPattern;
use BackedEnum;
use App\Trees\Catalog\Models\Menu as MenuModel;
use App\Trees\Catalog\Enums\MenuCategory; // Import your Enum class
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;

class Menu extends Page implements HasActions, HasTable, HasForms
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithForms; // Required when implementing HasForms

    protected static string | UnitEnum | null $navigationGroup = 'Catalog';
    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-menu';
    protected static ?string $navigationLabel = 'Menu';
    protected static ?int $navigationSort = 31;

    protected static ?string $title = 'Menu';
    protected ?string $subheading = 'Catalog of all company links.';

    protected string $view = 'staff.pages.menu';

    public string $activeTab = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                MenuModel::query()
                    // Only filter by category if the active tab is NOT 'all'
                    ->when(
                        $this->activeTab !== 'all',
                        fn(Builder $query) => $query->where('category', $this->activeTab)
                    )
            )
            ->columns([
                Split::make([
                    ImageColumn::make('icon')
                        ->label('')
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl('https://raw.githubusercontent.com/bit-ecosystem/bites/refs/heads/main/menu/business-idea.svg'),
                    Stack::make([
                        TextColumn::make('title')
                            ->label('Title')
                            ->color('primary'),
                        TextColumn::make('description')
                            ->size(TextSize::ExtraSmall)
                            ->wrap(),
                    ]),
                ]),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->recordUrl(
                fn(MenuModel $model): string => $model->internal_link && Route::has($model->internal_link)
                    ? route($model->internal_link)
                    : ($model->currentVersion()?->value('content_url') ?? '#')
            )
            ->filters([])
            ->toolbarActions([]);
    }
}
