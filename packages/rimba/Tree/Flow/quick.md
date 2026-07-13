# PHP Files Code Dump
*Generated on: 2026-07-13 15:44:08*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow`*

---

## File: `database\migrations\0002_01_01_000611_create_workflows_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\database\migrations\0002_01_01_000611_create_workflows_tables.php`

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

        Schema::create('workflow_blueprints', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->foreignId('org_teams_id')->nullable()->constrained('org_teams');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('workflow_nodes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            $table->foreignId('work_package_id')->nullable()->constrained('work_packages');
            $table->string('name');
            $table->string('type')->index();
            $table->timestamps();
        });
        Schema::create('workflow_transitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            $table->foreignId('from_node_id')->constrained('workflow_nodes');
            $table->foreignId('to_node_id')->constrained('workflow_nodes');
            $table->string('name')->nullable();
            $table->string('action')->nullable();
            $table->text('condition')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            // $table->string('trackable_id')->nullable();
            // $table->string('trackable_type')->nullable();
            $table->string('status')->default('active');
            $table->morphs('trackable');
            $table->timestamps();
        });
        Schema::create('workflow_node_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflowinstances');
            $table->foreignId('workflow_node_id')->constrained('workflow_nodes');
            $table->timestamp('activated_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_transition_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflowinstances');
            $table->foreignId('workflow_transition_id')->constrained('workflow_transitions');
            $table->timestamp('executed_at');
            $table->foreignId('executed_by_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('workflow_blueprint_role', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflow_blueprints')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            $table->unique(['workflow_blueprint_id', 'role_id'], 'workflow_blueprint_role_unique');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_blueprint_role');
        Schema::dropIfExists('workflow_transition_instances');
        Schema::dropIfExists('workflow_node_instances');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_nodes');
        Schema::dropIfExists('workflow_blueprints');
    }
};

```

---

## File: `src\FlowServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\FlowServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow;

use App\Services\BitesServiceProvider;

class FlowServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\WorkflowBlueprint.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowBlueprint.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

#[Fillable([
    'name',
    'owner',
    'active',
])]
class WorkflowBlueprint extends Model
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
            'owner' => 'integer',
            'active' => 'boolean',
        ];
    }

    public function workflowNodes(): HasMany
    {
        return $this->hasMany(WorkflowNode::class);
    }

    public function owner(): BelongsTo
    {
        return $this->BelongsTo(OrgTeam::class);
    }

    public function requesterRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'workflow_blueprint_role');
    }
}

```

---

## File: `src\Models\WorkflowInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'workflow_blueprint_id',
    'trackable_id',
    'trackable_type',
    'status',
])]
class WorkflowInstance extends Model
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
            'workflow_blueprint_id' => 'integer',
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function trackable(): MorphTo
    {
        return $this->morphTo();
    }

    public function workflowNodeInstances(): HasMany
    {
        return $this->hasMany(WorkflowNodeInstance::class);
    }

    public function workflowTransitionInstances(): HasMany
    {
        return $this->hasMany(WorkflowTransitionInstance::class);
    }
}

```

---

## File: `src\Models\WorkflowNode.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowNode.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rimba\Tree\Work\Models\WorkPackage;

#[Fillable([
    'workflow_blueprint_id',
    'work_package_id',
    'name',
    'type',
])]
class WorkflowNode extends Model
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
            'workflow_blueprint_id' => 'integer',
            'work_package_id' => 'integer',
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function workPackage(): BelongsTo
    {
        return $this->BelongsTo(WorkPackage::class);
    }

    public function toPath(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_node_id');
    }

    public function fromPath(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'to_node_id');
    }
}

```

---

## File: `src\Models\WorkflowNodeInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowNodeInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workflow_instance_id',
    'workflow_node_id',
    'activated_at',
    'completed_at',
])]
class WorkflowNodeInstance extends Model
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
            'workflow_node_id' => 'integer',
            'activated_at' => 'timestamp',
            'completed_at' => 'timestamp',
        ];
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function workflowNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}

```

---

## File: `src\Models\WorkflowTransition.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowTransition.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workflow_blueprint_id',
    'from_node_id',
    'to_node_id',
    'name',
    'action',
    'condition',
])]
class WorkflowTransition extends Model
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
            'workflow_blueprint_id' => 'integer',
            'from_node_id' => 'integer',
            'to_node_id' => 'integer',
            'workflow_node_id' => 'integer',
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}

```

---

## File: `src\Models\WorkflowTransitionInstance.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Flow\src\Models\WorkflowTransitionInstance.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workflow_instance_id',
    'workflow_transition_id',
    'executed_at',
    'executed_by_id',
])]
class WorkflowTransitionInstance extends Model
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
            'workflow_transition_id' => 'integer',
            'executed_at' => 'timestamp',
            'executed_by_id' => 'integer',
        ];
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function workflowTransition(): BelongsTo
    {
        return $this->belongsTo(WorkflowTransition::class);
    }

    public function executedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

```

---

