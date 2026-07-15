# PHP Files Code Dump
*Generated on: 2026-07-14 16:20:45*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\agreement`*

---

## File: `database\migrations\0002_01_01_000602_create_agreements_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\database\migrations\0002_01_01_000602_create_agreements_tables.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('agreement_types', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->longText('template')->nullable();
            $table->json('public_schema')->nullable();
            $table->json('confidential_schema')->nullable();
            $table->json('notify')->nullable();
            $table->integer('expiry_notify_days')->default(30);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->foreignId('workflow_id')->nullable()->constrained();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
        Schema::create('agreements', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('agreement_type');
            $table->string('contract_no')->nullable();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'expired', 'terminated', 'archived'])->default('draft');
            $table->json('terms')->nullable();
            $table->json('meta')->nullable();
            // $table->morphs('contractable');
            $table->timestamps();
        });
        Schema::create('parties', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agreement_id')->constrained('agreements');
            $table->string('role')->nullable();
            $table->boolean('is_signatory')->default(false);
            $table->boolean('notify_on_expiry')->default(true);
            $table->json('meta')->nullable();
            $table->morphs('party');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_parties');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_types');
    }
};

```

---

## File: `src\AgreementServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\AgreementServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement;

use Bites\Base\Services\BitesServiceProvider;

class AgreementServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\CreateAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\EditAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\ListAgreementTypes;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\ViewAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeForm;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeInfolist;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Tables\AgreementTypesTable;
use Bites\Agreement\Models\AgreementType;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AgreementTypeResource extends Resource
{
    protected static ?string $model = AgreementType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Agreement Type';

    protected static ?int $navigationSort = 62;

    public static function form(Schema $schema): Schema
    {
        return AgreementTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgreementTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgreementTypesTable::configure($table);
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
            'index' => ListAgreementTypes::route('/'),
            'create' => CreateAgreementType::route('/create'),
            'view' => ViewAgreementType::route('/{record}'),
            'edit' => EditAgreementType::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Pages\CreateAgreementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Pages\CreateAgreementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementType extends CreateRecord
{
    protected static string $resource = AgreementTypeResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Pages\EditAgreementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Pages\EditAgreementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAgreementType extends EditRecord
{
    protected static string $resource = AgreementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Pages\ListAgreementTypes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Pages\ListAgreementTypes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgreementTypes extends ListRecords
{
    protected static string $resource = AgreementTypeResource::class;

    protected static ?string $title = 'Agreement Types';

    protected ?string $subheading = 'Types of agreements that can be created.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Pages\ViewAgreementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Pages\ViewAgreementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreementType extends ViewRecord
{
    protected static string $resource = AgreementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AgreementTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('template')
                    ->columnSpanFull(),
                Textarea::make('public_schema')
                    ->columnSpanFull(),
                Textarea::make('confidential_schema')
                    ->columnSpanFull(),
                Textarea::make('notify')
                    ->columnSpanFull(),
                TextInput::make('expiry_notify_days')
                    ->required()
                    ->numeric()
                    ->default(30),
                Toggle::make('requires_approval')
                    ->required(),
                Toggle::make('requires_signature')
                    ->required(),
                Select::make('workflow_id')
                    ->relationship('workflow', 'id'),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgreementTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('name'),
                TextEntry::make('code'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('template')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('public_schema')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('confidential_schema')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notify')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('expiry_notify_days')
                    ->numeric(),
                IconEntry::make('requires_approval')
                    ->boolean(),
                IconEntry::make('requires_signature')
                    ->boolean(),
                TextEntry::make('workflow.id')
                    ->label('Workflow')
                    ->placeholder('-'),
                TextEntry::make('meta')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AgreementTypes\Tables\AgreementTypesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\AgreementTypes\Tables\AgreementTypesTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgreementTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('expiry_notify_days')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('requires_approval')
                    ->boolean(),
                IconColumn::make('requires_signature')
                    ->boolean(),
                TextColumn::make('workflow.id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\AgreementResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\AgreementResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\CreateAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\EditAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\ListAgreements;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\ViewAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas\AgreementForm;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas\AgreementInfolist;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Tables\AgreementsTable;
use Bites\Agreement\Models\Agreement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AgreementResource extends Resource
{
    protected static ?string $model = Agreement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Agreement';

    protected static ?int $navigationSort = 61;

    public static function form(Schema $schema): Schema
    {
        return AgreementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgreementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgreementsTable::configure($table);
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
            'index' => ListAgreements::route('/'),
            'create' => CreateAgreement::route('/create'),
            'view' => ViewAgreement::route('/{record}'),
            'edit' => EditAgreement::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Pages\CreateAgreement.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Pages\CreateAgreement.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreement extends CreateRecord
{
    protected static string $resource = AgreementResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Pages\EditAgreement.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Pages\EditAgreement.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAgreement extends EditRecord
{
    protected static string $resource = AgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Pages\ListAgreements.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Pages\ListAgreements.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgreements extends ListRecords
{
    protected static string $resource = AgreementResource::class;

    protected static ?string $title = 'Agreements';

    protected ?string $subheading = 'Binding agreement between parties for a specific purpose. Non private and confidential content of a contract agreementonly.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Pages\ViewAgreement.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Pages\ViewAgreement.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreement extends ViewRecord
{
    protected static string $resource = AgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Schemas\AgreementForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Schemas\AgreementForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('agreement_type')
                    ->required(),
                TextInput::make('contract_no'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('summary')
                    ->columnSpanFull(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                DatePicker::make('renewal_date'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                Textarea::make('terms')
                    ->columnSpanFull(),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Schemas\AgreementInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Schemas\AgreementInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgreementInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('agreement_type'),
                TextEntry::make('contract_no')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('summary')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('renewal_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('terms')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('meta')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Agreements\Tables\AgreementsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Agreements\Tables\AgreementsTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgreementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uuid')
                    ->label('UUID')
                    ->searchable(),
                TextColumn::make('agreement_type')
                    ->searchable(),
                TextColumn::make('contract_no')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('renewal_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\Pages\CreateParty.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\Pages\CreateParty.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParty extends CreateRecord
{
    protected static string $resource = PartyResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\Pages\EditParty.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\Pages\EditParty.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParty extends EditRecord
{
    protected static string $resource = PartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\Pages\ListParties.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\Pages\ListParties.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParties extends ListRecords
{
    protected static string $resource = PartyResource::class;

    protected static ?string $title = 'Parties';

    protected ?string $subheading = 'Individuals or entities involved in an agreement.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\PartyResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\PartyResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\CreateParty;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\EditParty;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\ListParties;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Schemas\PartyForm;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Tables\PartiesTable;
use Bites\Agreement\Models\Party;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartyResource extends Resource
{
    protected static ?string $model = Party::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'role';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Party';

    protected static ?int $navigationSort = 63;

    public static function form(Schema $schema): Schema
    {
        return PartyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartiesTable::configure($table);
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
            'index' => ListParties::route('/'),
            'create' => CreateParty::route('/create'),
            'edit' => EditParty::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\Schemas\PartyForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\Schemas\PartyForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('agreement_id')
                    ->relationship('agreement', 'title')
                    ->required(),
                TextInput::make('role'),
                Toggle::make('is_signatory')
                    ->required(),
                Toggle::make('notify_on_expiry')
                    ->required(),
                Textarea::make('meta')
                    ->columnSpanFull(),
                TextInput::make('party_type')
                    ->required(),
                TextInput::make('party_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Parties\Tables\PartiesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Http\UI\Admin\Resources\Parties\Tables\PartiesTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agreement.title')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                IconColumn::make('is_signatory')
                    ->boolean(),
                IconColumn::make('notify_on_expiry')
                    ->boolean(),
                TextColumn::make('party_type')
                    ->searchable(),
                TextColumn::make('party_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

```

---

## File: `src\Models\Agreement.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\Agreement.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use App\Trees\Organization\Models\OrgCorp;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'uuid',
    'agreement_type',
    'org_corp_id',
    'contract_no',
    'title',
    'summary',
    'start_date',
    'end_date',
    'renewal_date',
    'status',
    'terms',
    'meta',
])]
class Agreement extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'org_corp_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_date' => 'date',
            'terms' => 'array',
            'meta' => 'array',
        ];
    }

    public function agreementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}

```

---

## File: `src\Models\AgreementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\AgreementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Repo\App\Process\Models\Workflow;

#[Fillable([
    'uuid',
    'name',
    'code',
    'description',
    'template',
    'public_schema',
    'confidential_schema',
    'notify',
    'expiry_notify_days',
    'requires_approval',
    'requires_signature',
    'workflow_id',
    'meta',
])]
class AgreementType extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'public_schema' => 'array',
            'confidential_schema' => 'array',
            'notify' => 'array',
            'requires_approval' => 'boolean',
            'requires_signature' => 'boolean',
            'workflow_id' => 'integer',
            'meta' => 'array',
        ];
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}

```

---

## File: `src\Models\Party.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\Party.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'contract_id',
    'role',
    'is_signatory',
    'notify_on_expiry',
    'meta',
])]
class Party extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'contract_id' => 'integer',
            'is_signatory' => 'boolean',
            'notify_on_expiry' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function party(): MorphTo
    {
        return $this->morphTo();
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }
}

```

---

