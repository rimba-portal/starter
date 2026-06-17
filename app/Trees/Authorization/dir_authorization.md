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