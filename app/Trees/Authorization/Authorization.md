```text
app/Trees/Authorization/
├── Actions/
│   ├── AuthorizeAction.php
│   ├── AssignRoleToStaff.php
│   ├── SyncStaffRolesFromJobPosition.php
│   ├── EvaluateAccessAction.php
│   └── RevokeRoleFromStaff.php
│
├── Services/
│   ├── AuthorizationService.php
│   ├── AbacEngineService.php
│   ├── PermissionResolverService.php
│   ├── RoleSyncService.php
│   └── PolicyDecisionService.php
│
├── Policies/
│   ├── BasePolicy.php
│   ├── TaskPolicy.php
│   ├── DocumentPolicy.php
│   ├── UserPolicy.php
│   └── JobPositionPolicy.php
│
├── Rules/
│   ├── AbacRuleInterface.php
│   ├── ClearanceRule.php
│   ├── OwnershipRule.php
│   ├── OrgScopeRule.php
│   └── AttributeMatchRule.php
│
├── DTO/
│   ├── AuthorizationContext.php
│   ├── AccessDecision.php
│   └── PermissionRequest.php
│
├── Events/
│   ├── AccessGranted.php
│   ├── AccessDenied.php
│   ├── RoleAssigned.php
│   ├── RoleRevoked.php
│   └── PolicyEvaluated.php
│
├── Builders/
│   ├── PermissionQueryBuilder.php
│   └── RoleQueryBuilder.php
│
└── Traits/
    ├── HasAuthorizationContext.php
    └── InteractsWithPolicies.php
```

```text
app/Trees/Authorization/Filament/
├── Providers/
│   ├── AuthorizationPanelProvider.php
│   ├── AdminPanelProvider.php
│   └── StaffPanelProvider.php
│
├── Gates/
│   ├── FilamentGate.php
│   ├── ResourceGateResolver.php
│   └── PageGateResolver.php
│
├── Middleware/
│   ├── EnsureStaffLinked.php
│   ├── ResolveAuthorizationContext.php
│   └── EnforcePanelAccess.php
│
├── Resources/
│   ├── BaseResource.php
│   └── Concerns/
│       ├── HasAuthorization.php
│       ├── HasAbacVisibility.php
│       └── HasRbacChecks.php
│
├── Pages/
│   ├── BasePage.php
│   └── Concerns/
│       ├── AuthorizesPageAccess.php
│       └── ResolvesStaffContext.php
│
├── Widgets/
│   ├── BaseWidget.php
│   └── AuthorizationAwareWidget.php
│
└── Helpers/
    ├── FilamentAuth.php
    └── StaffContext.php
```

```code
<?php

namespace App\Trees\Authorization\Services;

use App\Trees\Authorization\DTO\AuthorizationContext;
use App\Trees\Authorization\DTO\AccessDecision;

class AuthorizationService
{
    public function authorize(AuthorizationContext $context): AccessDecision
    {
        // 1. Resolve Staff
        $staff = $context->staff;

        // 2. Step 1: RBAC check (Spatie fast fail)
        if (! $this->checkRbac($staff, $context)) {
            return AccessDecision::deny('RBAC_DENIED');
        }

        // 3. Step 2: Policy check
        if (! $this->checkPolicy($staff, $context)) {
            return AccessDecision::deny('POLICY_DENIED');
        }

        // 4. Step 3: ABAC evaluation
        if (! $this->checkAbac($staff, $context)) {
            return AccessDecision::deny('ABAC_DENIED');
        }

        // 5. Success
        return AccessDecision::allow();
    }

    private function checkRbac($staff, $context): bool
    {
        return $staff->hasPermissionTo($context->permission);
    }

    private function checkPolicy($staff, $context): bool
    {
        return app($context->policy)
            ->{$context->action}($staff, $context->resource);
    }

    private function checkAbac($staff, $context): bool
    {
        return app(AbacEngineService::class)
            ->evaluate($staff, $context);
    }
}

```

```code
<?php

namespace App\Trees\Authorization\Services;

use App\Trees\Authorization\DTO\AuthorizationContext;

class AbacEngineService
{
    public function evaluate($staff, AuthorizationContext $context): bool
    {
        $rules = [
            ClearanceRule::class,
            OwnershipRule::class,
            OrgScopeRule::class,
            AttributeMatchRule::class,
        ];

        foreach ($rules as $ruleClass) {
            $rule = app($ruleClass);

            if (! $rule->evaluate($staff, $context)) {
                return false;
            }
        }

        return true;
    }
}

```

```code
<?php

namespace App\Trees\Authorization\Rules;

use App\Trees\Authorization\DTO\AuthorizationContext;

interface AbacRuleInterface
{
    public function evaluate($staff, AuthorizationContext $context): bool;
}

class ClearanceRule implements AbacRuleInterface
{
    public function evaluate($staff, AuthorizationContext $context): bool
    {
        return $staff->clearance_level >= $context->resource->required_clearance;
    }
}
class OwnershipRule implements AbacRuleInterface
{
    public function evaluate($staff, AuthorizationContext $context): bool
    {
        if (! $context->resource->owner_id) {
            return true;
        }

        return $context->resource->owner_id === $staff->id;
    }
}

```

```code
<?php

namespace

class OrgScopeRule implements AbacRuleInterface
{
    public function evaluate($staff, AuthorizationContext $context): bool
    {
        return $staff->org_team_id === $context->resource->org_team_id;
    }
}

```

```code
<?php

namespace App\Trees\Authorization\DTO;

class AuthorizationContext
{
    public function __construct(
        public mixed $staff,
        public string $permission,
        public string $action,
        public mixed $resource,
        public ?string $policy = null,
    ) {}
}
```

```code
<?php

namespace App\Trees\Authorization\DTO;

class AccessDecision
{
    public function __construct(
        public bool $allowed,
        public ?string $reason = null
    ) {}

    public static function allow(): self
    {
        return new self(true);
    }

    public static function deny(string $reason): self
    {
        return new self(false, $reason);
    }
}

```

```code
<?php

namespace App\Trees\Authorization\Policies;

use App\Trees\Authorization\Services\AuthorizationService;
use App\Trees\Authorization\DTO\AuthorizationContext;

abstract class BasePolicy
{
    protected function authorize($staff, $permission, $action, $resource)
    {
        return app(AuthorizationService::class)
            ->authorize(
                new AuthorizationContext(
                    staff: $staff,
                    permission: $permission,
                    action: $action,
                    resource: $resource,
                    policy: static::class,
                )
            )->allowed;
    }
}

```

```code
<?php

namespace App\Trees\Authorization\Policies;

class TaskPolicy extends BasePolicy
{
    public function approve($staff, $task): bool
    {
        return $this->authorize(
            $staff,
            'approve task',
            'approve',
            $task
        );
    }

    public function view($staff, $task): bool
    {
        return $this->authorize(
            $staff,
            'view task',
            'view',
            $task
        );
    }
}

```

```code
<?php

namespace App\Trees\Authorization\Services;

class RoleSyncService
{
    public function syncFromJobPosition($staff)
    {
        if (! $staff->jobPosition) {
            return;
        }

        $roles = $staff->jobPosition->default_roles ?? [];

        $staff->syncRoles($roles);
    }
}
```

```code
<?php

namespace

class AccessDenied
{
    public function __construct(
        public $staff,
        public string $permission,
        public string $reason
    ) {}
}
```

```code
<?php

namespace

class AuthorizationPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->authGuard('web')
            ->middleware([
                \App\Trees\Authorization\Filament\Middleware\EnsureStaffLinked::class,
                \App\Trees\Authorization\Filament\Middleware\EnforcePanelAccess::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

```

```code
<?php

namespace

class EnsureStaffLinked
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (! $user?->staff) {
            abort(403, 'No Staff Profile Attached');
        }

        return $next($request);
    }
}

```

```code
<?php

namespace

class FilamentGate
{
    public static function check(string $permission, mixed $resource = null): bool
    {
        $staff = auth()->user()->staff;

        $context = new AuthorizationContext(
            staff: $staff,
            permission: $permission,
            action: $permission,
            resource: $resource,
            policy: null,
        );

        return app(AuthorizationService::class)
            ->authorize($context)
            ->allowed;
    }
}

```

```code
<?php

namespace

abstract class BaseResource extends Resource
{
    use HasAuthorization;

    public static function canViewAny(): bool
    {
        return FilamentGate::check('view_any_' . static::getModel());
    }

    public static function canCreate(): bool
    {
        return FilamentGate::check('create_' . static::getModel());
    }

    public static function canEdit($record): bool
    {
        return FilamentGate::check('update_' . static::getModel(), $record);
    }

    public static function canDelete($record): bool
    {
        return FilamentGate::check('delete_' . static::getModel(), $record);
    }
}

```

```code
<?php

namespace

trait AuthorizesPageAccess
{
    public static function canAccess(): bool
    {
        return FilamentGate::check(static::getPermission());
    }

    abstract protected static function getPermission(): string;
}

class Dashboard extends Page
{
    use AuthorizesPageAccess;

    protected static function getPermission(): string
    {
        return 'access_dashboard';
    }
}
```

```code
<?php

namespace

trait HasAbacVisibility
{
    public static function canViewField(string $field, $record = null): bool
    {
        $staff = auth()->user()->staff;

        return app(AbacEngineService::class)->evaluate(
            $staff,
            new AuthorizationContext(
                staff: $staff,
                permission: "view_field_{$field}",
                action: 'view_field',
                resource: $record,
            )
        );
    }
}
```


```text
TextInput::make('salary')
    ->hidden(fn () =>
        ! BaseResource::canViewField('salary')
    );
```


```code
<?php

namespace
class StaffContext
{
    public static function current()
    {
        return auth()->user()?->staff;
    }

    public static function jobPosition()
    {
        return self::current()?->jobPosition;
    }

    public static function team()
    {
        return self::current()?->org_team_id;
    }
}
```

```code
<?php

namespace

class EnforcePanelAccess
{
    public function handle($request, Closure $next)
    {
        $staff = auth()->user()->staff;

        if (! $staff) {
            abort(403);
        }

        if (! $staff->hasRole(['Admin', 'Manager', 'Staff'])) {
            abort(403, 'No panel access');
        }

        return $next($request);
    }
}