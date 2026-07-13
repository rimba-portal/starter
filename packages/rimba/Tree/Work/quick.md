# PHP Files Code Dump
*Generated on: 2026-07-13 15:44:13*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work`*

---

## File: `database\migrations\0002_01_01_000608_create_tasks_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\database\migrations\0002_01_01_000608_create_tasks_tables.php`

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

        Schema::create('work_packages', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('checklists', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_package_id')->constrained('workpackages');
            $table->string('name');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
        Schema::create('tasks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('checklist_id')->constrained('checklists');
            $table->foreignId('role_id')->constrained('roles');
            $table->string('description');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        Schema::create('work_package_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_package_id')->constrained('work_packages');
            $table->string('status')->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('checklist_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('work_package_instance_id')->constrained('workpackageinstances');
            $table->foreignId('checklist_id')->constrained('checklists');
            $table->string('status')->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('task_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('checklist_instance_id')->constrained('checklist_instances');
            $table->foreignId('task_id')->constrained('tasks');
            $table->foreignId('assigned_to_id')->nullable()->constrained('staff');
            $table->foreignId('completed_by_id')->nullable()->constrained('staff');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_instances');
        Schema::dropIfExists('checklist_instances');
        Schema::dropIfExists('work_package_instances');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('checklists');
        Schema::dropIfExists('work_packages');
    }
};

```

---

## File: `src\Http\UI\Widgets\MyPendingTasksWidget.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Http\UI\Widgets\MyPendingTasksWidget.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Http\UI\Widgets;

use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;
use Rimba\Tree\Work\Models\TaskInstance;

class MyPendingTasksWidget extends TableWidget
{
    protected static ?string $heading = 'My Pending Tasks';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                TaskInstance::query()
                    ->where('assigned_to_id', Auth::user()?->staff?->id)
                    ->where('is_completed', false)
            )
            ->columns([
                Tables\Columns\TextColumn::make('task.name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('checklistInstance.name')
                    ->label('Checklist')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_completed')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
            ])
            ->recordActions([
                Actions\Action::make('open')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (TaskInstance $record): string => route(
                        'filament.staff.resources.task-instances.edit',
                        $record
                    )),
            ]);
    }
}

```

---

## File: `src\Http\UI\Widgets\TaskStatsWidget.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Http\UI\Widgets\TaskStatsWidget.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Http\UI\Widgets;

use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Rimba\Tree\Work\Models\TaskInstance;

class TaskStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $staffId = Auth::user()?->staff?->id;

        return [
            Stat::make(
                'Assigned Tasks',
                TaskInstance::query()
                    ->where('assigned_to_id', $staffId)
                    ->count(),
            )
                ->description('Tasks assigned to you')
                ->icon(Heroicon::OutlinedClipboardDocumentList)
                ->color('info'),

            Stat::make(
                'Pending Tasks',
                TaskInstance::query()
                    ->where('assigned_to_id', $staffId)
                    ->where('is_completed', false)
                    ->count(),
            )
                ->description('Awaiting completion')
                ->icon(Heroicon::OutlinedClock)
                ->color('warning'),

            Stat::make(
                'Completed Tasks',
                TaskInstance::query()
                    ->where('assigned_to_id', $staffId)
                    ->where('is_completed', true)
                    ->count(),
            )
                ->description('Finished tasks')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->color('success'),

            Stat::make(
                'Completed By Me',
                TaskInstance::query()
                    ->where('completed_by_id', $staffId)
                    ->count(),
            )
                ->description('Tasks completed by you')
                ->icon(Heroicon::OutlinedUser)
                ->color('primary'),
        ];
    }
}

```

---

## File: `src\Http\UI\Widgets\UnassignedTasksByRoleWidget.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Http\UI\Widgets\UnassignedTasksByRoleWidget.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Http\UI\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Rimba\Tree\Work\Models\TaskInstance;

class UnassignedTasksByRoleWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Unassigned Tasks By Role';

    protected function getStats(): array
    {
        $stats = [];

        $totalUnassigned = TaskInstance::query()
            ->whereNull('assigned_to_id')
            ->where('is_completed', false)
            ->count();

        $stats[] = Stat::make('Total Unassigned', number_format($totalUnassigned))
            ->description('Pending tasks without assigned staff')
            ->color($totalUnassigned > 0 ? 'danger' : 'success')
            ->icon('heroicon-o-exclamation-triangle');

        $counts = TaskInstance::query()
            ->join('tasks', 'task_instances.task_id', '=', 'tasks.id')
            ->join('roles', 'tasks.role_id', '=', 'roles.id')
            ->whereNull('task_instances.assigned_to_id')
            ->where('task_instances.is_completed', false)
            ->select([
                'roles.id as role_id',
                'roles.name as role_name',
                DB::raw('COUNT(task_instances.id) as total'),
            ])
            ->groupBy('roles.id', 'roles.name')
            ->orderByDesc('total')
            ->get();

        foreach ($counts as $count) {
            $stats[] = Stat::make(
                str($count->role_name)->headline()->toString(),
                number_format((int) $count->total),
            )
                ->description('Unassigned pending tasks')
                ->color('warning')
                ->icon('heroicon-o-user-group');
        }

        return $stats;
    }
}

```

---

## File: `src\Models\Checklist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\Checklist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'work_package_id',
    'name',
    'sort_order',
])]
class Checklist extends Model
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
            'work_package_id' => 'integer',
        ];
    }

    public function workPackage(): BelongsTo
    {
        return $this->belongsTo(WorkPackage::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}

```

---

## File: `src\Models\ChecklistInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\ChecklistInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'work_package_instance_id',
    'checklist_id',
    'status',
    'activated_at',
    'completed_at',
])]
class ChecklistInstance extends Model
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
            'work_package_instance_id' => 'integer',
            'checklist_id' => 'integer',
            'activated_at' => 'timestamp',
            'completed_at' => 'timestamp',
        ];
    }

    public function workPackageInstance(): BelongsTo
    {
        return $this->belongsTo(WorkPackageInstance::class);
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }
}

```

---

## File: `src\Models\Task.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\Task.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role;

#[Fillable([
    'checklist_id',
    'role_id',
    'description',
    'active',
])]
class Task extends Model
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
            'checklist_id' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}

```

---

## File: `src\Models\TaskInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\TaskInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'checklist_instance_id',
    'task_id',
    'assigned_to_id',
    'completed_by_id',
    'is_completed',
    'completed_at',
])]
class TaskInstance extends Model
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
            'work_package_instance_id' => 'integer',
            'checklist_instance_id' => 'integer',
            'task_id' => 'integer',
            'assigned_to_id' => 'integer',
            'completed_by_id' => 'integer',
            'is_completed' => 'boolean',
            'completed_at' => 'timestamp',
        ];
    }

    public function workPackageInstance(): BelongsTo
    {
        return $this->belongsTo(WorkPackageInstance::class);
    }

    public function checklistInstance(): BelongsTo
    {
        return $this->belongsTo(ChecklistInstance::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

```

---

## File: `src\Models\WorkPackage.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\WorkPackage.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'active',
])]
class WorkPackage extends Model
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
            'active' => 'boolean',
        ];
    }

    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class);
    }
}

```

---

## File: `src\Models\WorkPackageInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\Models\WorkPackageInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'work_package_id',
    'status',
    'started_at',
    'completed_at',
])]
class WorkPackageInstance extends Model
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
            'workflow_instance_id' => 'integer',
            'started_at' => 'timestamp',
            'completed_at' => 'timestamp',
        ];
    }

    // public function workPackage(): BelongsTo
    // {
    //     return $this->belongsTo(WorkPackage::class);
    // }

    public function checklistInstances(): HasMany
    {
        return $this->hasMany(ChecklistInstance::class);
    }

    public function taskInstances(): HasMany
    {
        return $this->hasMany(TaskInstance::class);
    }
}

```

---

## File: `src\WorkServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Work\src\WorkServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Work;

use App\Services\BitesServiceProvider;

class WorkServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

