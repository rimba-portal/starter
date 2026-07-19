<?php

declare(strict_types=1);

namespace Bites\Attributing;

use Bites\Attributing\Macros\LockWhenFilledMacro;
use Bites\Attributing\Services\AbacEngine;
use Bites\Base\Services\BitesServiceProvider;
use Illuminate\Support\Facades\Gate;

class AttributingServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        LockWhenFilledMacro::register();

        // Register Global Interceptor to catch structural ABAC failures before standard routes
        $this->app->booted(function () {
            Gate::before(function ($user, $ability, $args) {
                $resource = current($args) ?: null;

                if ($resource instanceof \Illuminate\Database\Eloquent\Model) {
                    $engine = app(AbacEngine::class);
                    
                    if (!$engine->evaluate($user, $resource, $ability)) {
                        return false; // Intercept access and fail explicitly
                    }
                }
                
                return null; // Fall through naturally to Spatie RBAC role models
            });
        });
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Policies;

use Bites\Attributing\Services\AbacEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DynamicResourcePolicy
{
    protected AbacEngine $abacEngine;

    public function __construct(AbacEngine $abacEngine)
    {
        $this->abacEngine = $abacEngine;
    }

    public function viewAny(Model $user, string $modelClass): bool
    {
        return $user->can('view ' . $this->resolveFamily($modelClass));
    }

    public function view(Model $user, Model $resource): bool
    {
        $action = 'view ' . $this->resolveFamily(get_class($resource));
        return $user->can($action) && $this->abacEngine->evaluate($user, $resource, $action);
    }

    public function create(Model $user, string $modelClass): bool
    {
        return $user->can('create ' . $this->resolveFamily($modelClass));
    }

    public function update(Model $user, Model $resource): bool
    {
        $action = 'edit ' . $this->resolveFamily(get_class($resource));
        return $user->can($action) && $this->abacEngine->evaluate($user, $resource, $action);
    }

    public function delete(Model $user, Model $resource): bool
    {
        $family = $this->resolveFamily(get_class($resource));
        $action = $family === 'thing' ? 'retire assets' : "delete {$family}";
        
        return $user->can($action) && $this->abacEngine->evaluate($user, $resource, $action);
    }

    protected function resolveFamily(string $className): string
    {
        $baseName = Str::lower(class_basename($className));

        return match (true) {
            str_contains($baseName, 'user') || str_contains($baseName, 'staff') || str_contains($baseName, 'person') => 'personnel',
            str_contains($baseName, 'location') || str_contains($baseName, 'facility') => 'locations',
            default => 'assets',
        };
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Services;

use Bites\Attributing\Models\AttributeDefinition;
use Illuminate\Database\Eloquent\Model;

class AbacEngine
{
    /**
     * Core evaluation interceptor matching Subject context against Resource requirements.
     */
    public function evaluate(Model $subject, ?Model $resource, string $action): bool
    {
        // 1. Fetch dynamic rules setup inside the configuration dashboard
        $activeAbacRules = AttributeDefinition::where('is_active', true)
            ->where('is_abac', true)
            ->get();

        foreach ($activeAbacRules as $rule) {
            if (!$this->ruleApplies($rule, $resource, $action)) {
                continue;
            }

            if (!$this->passesRule($subject, $resource, $rule)) {
                return false; // Break immediately if an attribute block matches
            }
        }

        return true;
    }

    protected function ruleApplies(AttributeDefinition $rule, ?Model $resource, string $action): bool
    {
        if (empty($rule->applies_to)) {
            return false;
        }

        $targetAction = $rule->applies_to['action'] ?? null;
        if ($targetAction && $targetAction !== $action) {
            return false;
        }

        return true;
    }

    protected function passesRule(Model $subject, ?Model $resource, AttributeDefinition $rule): bool
    {
        // Pull context out from the Actor (Subject)
        $subjectValue = method_exists($subject, 'getDynamicAttribute') 
            ? $subject->getDynamicAttribute($rule->key) 
            : null;

        // Pull context requirements out from the data target (Resource)
        $resourceRequirement = ($resource && method_exists($resource, 'getDynamicAttribute'))
            ? $resource->getDynamicAttribute($rule->key)
            : null;

        // Handle structural evaluation logic rules
        return match ($rule->key) {
            'security_clearance_level' => (int)$subjectValue >= (int)($resourceRequirement ?? 0),
            'fit_for_duty' => $subjectValue === 'true' || $subjectValue === true,
            'operational_status' => $resourceRequirement !== 'decommissioned',
            default => $resourceRequirement !== null ? ($subjectValue === $resourceRequirement) : true
        };
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

trait HasDynamicAttributes
{
    /**
     * Dynamically map the target model to its specific EAV table family.
     */
    public function dynamicAttributes(): MorphMany
    {
        $className = Str::lower(class_basename($this));

        $family = match(true) {
            str_contains($className, 'user') || str_contains($className, 'staff') || str_contains($className, 'person') => 'Person',
            str_contains($className, 'location') || str_contains($className, 'facility') => 'Location',
            default => 'Thing',
        };

        return $this->morphMany("Bites\\Attributing\\Models\\{$family}Attribute", 'attributable');
    }

    /**
     * Helper runtime getter to retrieve an EAV attribute value by its string key.
     */
    public function getDynamicAttribute(string $key): mixed
    {
        return $this->dynamicAttributes()->where('key', $key)->value('value');
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LocationAttribute extends Model
{
    protected $table = 'location_attributes';
    protected $fillable = ['key', 'value', 'attributable_id', 'attributable_type'];

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ThingAttribute extends Model
{
    protected $table = 'thing_attributes';
    protected $fillable = ['key', 'value', 'attributable_id', 'attributable_type'];

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PersonAttribute extends Model
{
    protected $table = 'person_attributes';
    protected $fillable = ['key', 'value', 'attributable_id', 'attributable_type'];

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }
}
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeDefinition extends Model
{
    protected $table = 'attribute_definitions';

    protected $fillable = [
        'family', 'group', 'key', 'name', 'description', 
        'applies_to', 'has_options', 'is_active', 'is_abac', 'is_system'
    ];

    protected $casts = [
        'applies_to' => 'array',
        'has_options' => 'boolean',
        'is_active' => 'boolean',
        'is_abac' => 'boolean',
        'is_system' => 'boolean',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}
