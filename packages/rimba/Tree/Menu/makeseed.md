seeding content
RIMBA MENU

1. Enterprise
2. People
3. Market
4. Supply
5. Operate
6. Technology


```text

Enterprise
│
├── Strategy Management
│   ├── Vision & Mission
│   ├── Strategic Planning
│   ├── Business Objectives
│   ├── OKR Management
│   └── Strategic Initiatives
│
├── Governance Management
│   ├── Corporate Governance
│   ├── Policy Management
│   ├── Compliance Management
│   ├── Risk Management
│   ├── Audit Management
│   └── Ethics Management
│
├── Financial Management
│   ├── Financial Planning
│   ├── Budget Management
│   ├── Accounting
│   ├── Cost Management
│   ├── Tax Management
│   └── Financial Reporting
│
├── Performance Management
│   ├── KPI Management
│   ├── Business Intelligence
│   ├── Management Reporting
│   └── Continuous Improvement
│
└── Legal Management
    ├── Contracts
    ├── Agreements
    ├── Intellectual Property
    └── Regulatory Affairs

```

```text

People
│
├── Workforce Planning
│   ├── Organization Structure
│   ├── Workforce Planning
│   └── Job Management
│
├── Talent Acquisition
│   ├── Recruitment
│   ├── Candidate Management
│   └── Onboarding
│
├── Employee Management
│   ├── Employee Profile
│   ├── Employee Lifecycle
│   ├── Attendance
│   ├── Leave
│   └── Employee Services
│
├── Performance & Development
│   ├── Performance Review
│   ├── Career Development
│   ├── Competency Management
│   └── Succession Planning
│
└── Learning Management
    ├── Training
    ├── Certification
    ├── Compliance Training
    └── Skills Management

```

```text

Market
│
├── Customer Management
│   ├── Customer Information
│   ├── Customer Relationship
│   ├── Customer Requests
│   └── Customer Feedback
│
├── Product Management
│   ├── Product Portfolio
│   ├── Product Lifecycle
│   ├── Product Catalogue
│   └── Product Documentation
│
├── Service Management
│   ├── Service Catalogue
│   ├── Service Requests
│   ├── Service Delivery
│   └── Service Performance
│
├── Sales Management
│   ├── Sales Planning
│   ├── Opportunity Management
│   └── Sales Reporting
│
└── Partner Management
    ├── Supplier Relationship
    ├── Vendor Management
    └── External Collaboration

```

```text

Supply
│
├── Supplier Management
│   ├── Supplier Registration
│   ├── Supplier Evaluation
│   └── Supplier Performance
│
├── Procurement Management
│   ├── Purchase Request
│   ├── Sourcing
│   ├── Purchase Order
│   └── Procurement Approval
│
├── Inventory Management
│   ├── Inventory Control
│   ├── Stock Movement
│   ├── Material Management
│   └── Inventory Reporting
│
├── Warehouse Management
│   ├── Receiving
│   ├── Storage
│   ├── Picking
│   └── Dispatch
│
└── Logistics Management
    ├── Transportation
    ├── Shipment
    └── Delivery Management

```

```text

Operate
│
├── Operations Planning
│   ├── Capacity Planning
│   ├── Scheduling
│   └── Resource Planning
│
├── Production Management
│   ├── Production Planning
│   ├── Work Orders
│   ├── Manufacturing Execution
│   └── Production Monitoring
│
├── Quality Management
│   ├── Quality Planning
│   ├── Inspection
│   ├── Non-Conformance
│   └── CAPA
│
├── Maintenance Management
│   ├── Asset Maintenance
│   ├── Preventive Maintenance
│   ├── Work Requests
│   └── Reliability Management
│
├── Safety Management
│   ├── Safety Inspection
│   ├── Incident Management
│   └── Emergency Response
│
└── Process Management
    ├── Workflow
    ├── Standard Processes
    └── Process Improvement

```

```text

Technology
│
├── Application Management
│   ├── Application Catalogue
│   ├── System Access
│   └── Application Support
│
├── Information Management
│   ├── Document Management
│   ├── Records Management
│   ├── Knowledge Management
│   └── Data Management
│
├── IT Service Management
│   ├── Incident
│   ├── Request
│   ├── Problem Management
│   └── Change Management
│
├── Security Management
│   ├── Identity Management
│   ├── Cybersecurity
│   ├── Access Control
│   └── Security Incident
│
└── Digital Innovation
    ├── AI Services
    ├── Automation
    ├── Analytics
    └── Integration



```
in K and S
```text

Knowledge
│
├── Document Management
│   ├── Policies
│   ├── SOPs
│   ├── Work Instructions
│   ├── Manuals
│   ├── Forms
│   ├── Templates
│   └── Records
│
├── Knowledge Base
│   ├── FAQs
│   ├── Best Practices
│   ├── Lessons Learned
│   ├── Troubleshooting
│   └── Technical Notes
│
├── Corporate Communication
│   ├── Announcements
│   ├── News
│   ├── Circulars
│   ├── Events
│   └── Town Hall
│
├── Standards
│   ├── ISO Standards
│   ├── Engineering Standards
│   ├── Design Standards
│   ├── Coding Standards
│   └── Operating Standards
│
├── Reports
│   ├── Dashboards
│   ├── KPIs
│   ├── Operational Reports
│   ├── Financial Reports
│   └── Management Reports
│
└── AI Knowledge
    ├── AI Assistant
    ├── Semantic Search
    ├── Knowledge Chat
    └── Recommendations

```

```text

Source
│
├── Organization
│   ├── Companies
│   ├── Business Units
│   ├── Departments
│   ├── Teams
│   ├── Positions
│   └── Organization Chart
│
├── People
│   ├── Staff
│   ├── Contractors
│   ├── Vendors
│   ├── Customers
│   └── Contacts
│
├── Products & Services
│   ├── Products
│   ├── Services
│   ├── Product Families
│   └── Catalogues
│
├── Resources
│   ├── Assets
│   ├── Equipment
│   ├── Materials
│   ├── Tools
│   ├── Vehicles
│   └── Facilities
│
├── Locations
│   ├── Sites
│   ├── Buildings
│   ├── Rooms
│   ├── Production Lines
│   ├── Warehouses
│   └── Storage Areas
│
├── Business
│   ├── Processes
│   ├── Workflows
│   ├── Projects
│   ├── Agreements
│   └── Contracts
│
└── Community
    ├── Employee Clubs
    ├── Communities of Practice
    ├── Interest Groups
    ├── Forums
    └── Marketplace

```



```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table): void {
            $table->id();

            $table->morphs('versionable');

            $table->string('version');
            $table->unsignedInteger('major');
            $table->unsignedInteger('minor');
            $table->unsignedInteger('patch');

            $table->string('status')->default('draft');

            $table->string('content_type')->nullable();
            $table->text('content_url');

            $table->string('checksum')->nullable();

            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};
```
```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Models;

use Bites\Versioning\Builders\VersionBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'major',
    'minor',
    'patch',
    'status',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'notes',
])]
class Version extends Model
{
    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
}
```
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table): void {

            $table->id();


            /*
             * BPM Category
             *
             * Enterprise
             * People
             * Market
             * Supply
             * Operate
             * Technology
             * Knowledge
             * Source
             */
            $table->string('category');


            /*
             * Group
             *
             * Example:
             *
             * Finance
             * HR
             * Production
             * Documents
             */
            $table->string('group')
                ->nullable();


            /*
             * Display
             */
            $table->string('name');
$table->string('description')
   
            $table->string('slug')
                ->unique();


            $table->string('icon')
                ->nullable();


            /*
             * Tree structure
             *
             * Category
             *   |
             *   Group
             *       |
             *       Menu Item
             */
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->nullOnDelete();


            /*
             * Permission checking
             */
            $table->string('permission')
                ->nullable();


            /*
             * Filament panel
             *
             * admin
             * staff
             */
            $table->string('panel')
                ->nullable();


            /*
             * Behaviour
             */
            $table->boolean('enabled')
                ->default(true);


            $table->boolean('visible')
                ->default(true);


            $table->boolean('open_new_tab')
                ->default(false);



            /*
             * Ordering
             */
            $table->unsignedInteger('sort')
                ->default(0);



            $table->timestamps();



            $table->index('category');

            $table->index('group');

            $table->index('parent_id');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
```
```php
<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Menu extends Model
{

    protected $fillable = [

        'category',

        'group',

        'name',

'description',

        'slug',

        'icon',

        'parent_id',

        'permission',

        'panel',

        'enabled',

        'visible',

        'open_new_tab',

        'sort',

    ];



    protected $casts = [

        'enabled' => 'boolean',

        'visible' => 'boolean',

        'open_new_tab' => 'boolean',

    ];



    /*
     |--------------------------------------------------------------------------
     | Version
     |--------------------------------------------------------------------------
     */


    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable'
        );
    }



    public function activeVersion()
    {
        return $this->morphOne(
            Version::class,
            'versionable'
        )
        ->where('status', 'released')
        ->latestOfMany('released_at');
    }



    /*
     |--------------------------------------------------------------------------
     | Menu Tree
     |--------------------------------------------------------------------------
     */


    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Menu::class,
            'parent_id'
        );
    }



    public function children(): HasMany
    {
        return $this->hasMany(
            Menu::class,
            'parent_id'
        )
        ->orderBy('sort');
    }



    /*
     |--------------------------------------------------------------------------
     | Scope
     |--------------------------------------------------------------------------
     */


    public function scopeVisible($query)
    {
        return $query->where('visible', true)
            ->where('enabled', true);
    }


}
```
```php
<?php

declare(strict_types=1);

namespace Database\Seeders;

use Bites\Attributing\Services\GetModelInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class JsonSeedThruModel extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $directoryPath = database_path('json_seeds');

        if (! File::exists($directoryPath)) {
            $this->command?->error("Directory not found at: {$directoryPath}");

            return;
        }

        foreach (File::files($directoryPath) as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $tableName = $file->getFilenameWithoutExtension();

            if (! Schema::hasTable($tableName)) {
                $this->command?->warn("Skipping file: '{$file->getFilename()}'. Table '{$tableName}' does not exist.");

                continue;
            }

            $modelClass = GetModelInfo::findByTable($tableName);

            if (! $modelClass) {
                $this->command?->warn("Skipping table '{$tableName}'. No Eloquent model found.");

                continue;
            }

            $jsonContent = File::get($file->getRealPath());
            $data = json_decode($jsonContent, true);

            if (empty($data)) {
                $this->command?->warn("Skipping table '{$tableName}': JSON file is empty or invalid.");

                continue;
            }

            if (isset($data[$tableName])) {
                $data = $data[$tableName];
            }

            if (! $this->isList($data)) {
                $data = [$data];
            }

            $this->command?->info("Model seeding table '{$tableName}' using {$modelClass}...");

            foreach ($data as $row) {
                if (! is_array($row)) {
                    continue;
                }

                $this->seedRow($modelClass, $row);
            }
        }

        $this->command?->info('Model JSON directory seeding completed successfully!');
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    protected function seedRow(string $modelClass, array $row): Model
    {
        /** @var Model $model */
        $model = new $modelClass;

        [$attributes, $relations] = $this->splitAttributesAndRelations($model, $row);

        $uniqueBy = $this->guessUniqueBy($attributes);

        if ($uniqueBy === []) {
            /** @var Model $record */
            $record = $modelClass::query()->create($attributes);
        } else {
            /** @var Model $record */
            $record = $modelClass::query()->updateOrCreate(
                $uniqueBy,
                $attributes
            );
        }

        foreach ($relations as $relationName => $items) {
            $this->seedRelation($record, $relationName, $items);
        }

        return $record;
    }

    protected function splitAttributesAndRelations(Model $model, array $row): array
    {
        $attributes = [];
        $relations = [];

        foreach ($row as $key => $value) {
            if (
                is_array($value)
                && method_exists($model, $key)
                && $this->isRelation($model, $key)
            ) {
                $relations[$key] = $value;

                continue;
            }

            $attributes[$key] = $value;
        }

        return [$attributes, $relations];
    }

    protected function isRelation(Model $model, string $method): bool
    {
        try {
            return $model->{$method}() instanceof Relation;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function seedRelation(Model $parent, string $relationName, array $items): void
    {
        if (! $this->isList($items)) {
            $items = [$items];
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $uniqueBy = $this->guessUniqueBy($item);

            if ($uniqueBy === []) {
                $parent->{$relationName}()->create($item);

                continue;
            }

            $parent->{$relationName}()->updateOrCreate(
                $uniqueBy,
                $item
            );
        }
    }

    protected function guessUniqueBy(array $attributes): array
    {
        foreach (['key', 'code', 'slug', 'value', 'name'] as $column) {
            if (array_key_exists($column, $attributes)) {
                return [
                    $column => $attributes[$column],
                ];
            }
        }

        return [];
    }

    protected function isList(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
```

