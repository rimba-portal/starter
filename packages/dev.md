# Starter Kit consist of

##  Foundation

### Organization Tree
Defines entities, hierarchy, teams, positions, and staff structure.

### Authorization Tree
Access control layer combining roles, permissions, and policies (RBAC + ABAC).

### FloorPlan Tree
Models physical spaces (buildings, rooms, zones) for location-aware operations.

### Branding Tree
Manages identity and UI customization (logos, themes, multi-tenant branding).

### Calendar Tree
Handles time structures like holidays, shifts, rosters, and events.

## Execution Engine

### Process Tree
Workflow engine that defines process flows, transitions, and automation logic.

### Todo Tree
Execution layer for tasks, assignments, checklists, and work tracking.

### Catalog Tree
Defines requestable services and offerings with forms and fulfillment entry points.

## Cross-Cutting / Infrastructure

### Agreement Tree
Lightweight representation of agreements (contracts, SLAs, MOUs) linking parties, assets, or services.

### AuditTrail Tree
System-wide activity log tracking actions, changes, and events for traceability and accountability.

### Copies Tree
Supports duplication, snapshotting, and controlled replication of records or content.

### Sync Tree
Integration layer for external systems (LDAP, APIs, imports/exports).


## Directory Reference Map

```text
app/Trees/<Tree name>/
└── Trees/<Tree name>/                           # any backend use utility classes
      ├── Actions/                  # Single business workflow classes (The "What")
      ├── Builders/                 # Custom database query scopes (The "Where")
      ├── Events/                   # Plain data structures reporting past system mutations
      ├── Http/UI/Admin/Resources   # Filament Resource for Admin Panel
      ├── Http/UI/Admin/Pages       # Filament Pages for Admin Panel
      ├── Http/UI/Admin/Widgets     # Filament Widgets for Admin Panel
      ├── Http/UI/Staff/Resources   # Filament Resource for Staff Panel
      ├── Http/UI/Staff/Pages       # Filament Pages for Staff Panel
      ├── Http/UI/Staff/Widgets     # Filament Widgets for Staff Panel
      ├── Http/API/Resources        # JSON API for Models classes
      ├── Jobs/                     # Asynchronous queue workers offloading network/heavy tasks
      ├── Listeners/                # Reactive workers waiting to handle specific Event payloads
      ├── Models/                   # Database relationships, column casting, and table mappings
      ├── Observers/                # Automated low-level lifecycle DB hooks
      ├── Policies/                 # Authorization checks guarding Models and Filament Resources
      └── Services/                 # Wrapper layer for third-party tools and complex algorithms
config/                         # configuration files
database/                       # database migrations
resources/
packages/                           # Add on packages
    └── Tree/                       # Business Modules
    └── Root/                       # Platform Modules
    └── Seed/                       # Content Marketplace if any
```