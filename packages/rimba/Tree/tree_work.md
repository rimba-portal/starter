```text
Rimba
└── Trees/
  └── <Tree name>/
    └── src                         
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
    └── config/                     
    └── database/                   # database migrations
    └── resources/               
```

```yaml
package: rimba/tree/work
dependencies: [rimba/core (containing Staff, Role and User)]

namespace Rimba\Tree\Work\Models;

models:
  WorkPackage:
    name: string
    description: text nullable
    active: boolean default:true
    relationships:
      hasMany: Checklist

  Checklist:
    work_package_id: foreign
    name: string
    sort_order: integer default:0
    relationships:
      belongsTo: WorkPackage
      hasMany: Task

  Task:
    checklist_id: foreign
    description: string
    active: boolean default:true
    relationships:
      belongsTo: Checklist, Role

  WorkPackageInstance:
    work_package_id: foreign
    status: string default:'active'
    started_at: timestamp nullable
    completed_at: timestamp nullable
    relationships:
      belongsTo: WorkPackage
      hasMany: ChecklistInstance, TaskInstance

  ChecklistInstance:
    work_package_instance_id: foreign
    checklist_id: foreign
    status: string default:'planned'
    activated_at: timestamp nullable
    completed_at: timestamp nullable
    relationships:
      belongsTo: WorkPackageInstance, Checklist

  TaskInstance:
    work_package_instance_id: foreign
    checklist_instance_id: foreign
    task_id: foreign
    status: string default:'open'
    assigned_to_id: nullable foreign users
    completed_by_id: nullable foreign users
    is_completed: boolean default:false
    completed_at: timestamp nullable
    relationships:
      belongsTo: WorkPackageInstance, ChecklistInstance, Task, Staff:assignedTo, Staff:completedBy
```

```text
rimba/tree/work
├── composer.json
│   # Package metadata, dependencies, PSR-4 autoloading, service provider registration.
│
├── README.md
│   # Installation guide, workflow concepts, usage examples, and package documentation.
│
├── config
│   └── work.php
│       # Package configuration such as default statuses, policies, role mappings,
│       # auto-assignment settings, and workflow behaviour.
│
├── database
│   └── migrations
│       ├── create_work_packages_table.php
│       │   # Creates reusable workflow templates.
│       │
│       ├── create_checklists_table.php
│       │   # Creates ordered stages belonging to WorkPackages.
│       │
│       ├── create_tasks_table.php
│       │   # Creates role-driven tasks belonging to Checklists.
│       │
│       ├── create_work_package_instances_table.php
│       │   # Creates runtime executions of WorkPackages.
│       │
│       ├── create_checklist_instances_table.php
│       │   # Creates runtime executions of Checklists.
│       │
│       └── create_task_instances_table.php
│           # Creates runtime executions of Tasks including assignment,
│           # completion and status tracking.
│
├── resources
│   ├── views
│   │   # Custom Blade views for Filament pages, widgets and actions.
│   │
│   ├── lang
│   │   # Translation strings.
│   │
│   └── icons
│       # Custom package SVG icons.
│
└── src
    ├── WorkServiceProvider.php
    │   # Registers migrations, views, config, events, listeners and policies.
    │
    ├── Actions
    │   ├── CreateWorkPackage.php
    │   │   # Creates a WorkPackage template.
    │   │
    │   ├── StartWorkPackage.php
    │   │   # Starts a WorkPackageInstance and dispatches WorkPackageStarted.
    │   │
    │   ├── ActivateChecklist.php
    │   │   # Marks a ChecklistInstance as pending/current stage.
    │   │
    │   ├── AssignTask.php
    │   │   # Assigns a queued TaskInstance to a Staff member.
    │   │
    │   ├── ReleaseTask.php
    │   │   # Removes assignment and returns task to queue.
    │   │
    │   ├── CompleteTask.php
    │   │   # Marks TaskInstance as completed and dispatches TaskCompleted.
    │   │
    │   ├── SkipTask.php
    │   │   # Marks TaskInstance as skipped.
    │   │
    │   ├── CancelTask.php
    │   │   # Marks TaskInstance as cancelled.
    │   │
    │   ├── CompleteChecklist.php
    │   │   # Marks ChecklistInstance complete when all Tasks are finished.
    │   │
    │   └── CompleteWorkPackage.php
    │       # Marks WorkPackageInstance completed when final Checklist finishes.
    │
    ├── Builders
    │   ├── WorkPackageBuilder.php
    │   │   # Custom query scopes for WorkPackages.
    │   │
    │   ├── ChecklistBuilder.php
    │   │   # Query helpers for checklist filtering and ordering.
    │   │
    │   ├── TaskBuilder.php
    │   │   # Query helpers for tasks and role filtering.
    │   │
    │   ├── WorkPackageInstanceBuilder.php
    │   │   # Query helpers for active/completed executions.
    │   │
    │   ├── ChecklistInstanceBuilder.php
    │   │   # Query helpers for pending/completed checklists.
    │   │
    │   └── TaskInstanceBuilder.php
    │       # Query helpers for queue, active and completed tasks.
    │
    ├── Events
    │   ├── WorkPackageStarted.php
    │   │   # Fired when a workflow execution begins.
    │   │
    │   ├── ChecklistActivated.php
    │   │   # Fired when a checklist becomes the active stage.
    │   │
    │   ├── ChecklistCompleted.php
    │   │   # Fired when all checklist tasks are finished.
    │   │
    │   ├── TaskAssigned.php
    │   │   # Fired when a task is assigned.
    │   │
    │   ├── TaskReleased.php
    │   │   # Fired when a task returns to the queue.
    │   │
    │   ├── TaskCompleted.php
    │   │   # Fired when a task is completed.
    │   │
    │   ├── TaskSkipped.php
    │   │   # Fired when a task is skipped.
    │   │
    │   ├── TaskCancelled.php
    │   │   # Fired when a task is cancelled.
    │   │
    │   └── WorkPackageCompleted.php
    │       # Fired when the workflow execution finishes.
    │
    ├── Listeners
    │   ├── CreateFirstChecklist.php
    │   │   # Creates the first ChecklistInstance after workflow start.
    │   │
    │   ├── GenerateTaskInstances.php
    │   │   # Creates queued TaskInstances for a ChecklistInstance.
    │   │
    │   ├── AutoCompleteChecklist.php
    │   │   # Checks if all tasks are closed and completes ChecklistInstance.
    │   │
    │   ├── ActivateNextChecklist.php
    │   │   # Creates and activates the next ChecklistInstance.
    │   │
    │   ├── AutoCompleteWorkPackage.php
    │   │   # Completes the WorkPackageInstance when last Checklist finishes.
    │   │
    │   └── WriteAuditLog.php
    │       # Writes audit entries to Trail package for workflow events.
    │
    ├── Models
    │   ├── WorkPackage.php
    │   │   # Workflow template root entity.
    │   │
    │   ├── Checklist.php
    │   │   # Ordered stage within a WorkPackage.
    │   │
    │   ├── Task.php
    │   │   # Task template associated with a Role.
    │   │
    │   ├── WorkPackageInstance.php
    │   │   # Runtime execution of a WorkPackage.
    │   │
    │   ├── ChecklistInstance.php
    │   │   # Runtime execution of a Checklist.
    │   │
    │   └── TaskInstance.php
    │       # Runtime execution of a Task.
    │
    ├── Observers
    │   ├── WorkPackageObserver.php
    │   │   # Handles model lifecycle automation for WorkPackages.
    │   │
    │   ├── ChecklistObserver.php
    │   │   # Handles checklist lifecycle automation.
    │   │
    │   ├── TaskObserver.php
    │   │   # Handles task lifecycle automation.
    │   │
    │   ├── WorkPackageInstanceObserver.php
    │   │   # Handles runtime execution lifecycle hooks.
    │   │
    │   ├── ChecklistInstanceObserver.php
    │   │   # Detects checklist state changes.
    │   │
    │   └── TaskInstanceObserver.php
    │       # Detects task assignment, release and completion changes.
    │
    ├── Policies
    │   ├── WorkPackagePolicy.php
    │   │   # Authorizes CRUD of WorkPackages.
    │   │
    │   ├── ChecklistPolicy.php
    │   │   # Restricts checklist management to OrgTeam admins.
    │   │
    │   ├── TaskPolicy.php
    │   │   # Restricts task management and role assignment.
    │   │
    │   ├── WorkPackageInstancePolicy.php
    │   │   # Controls workflow execution permissions.
    │   │
    │   ├── ChecklistInstancePolicy.php
    │   │   # Controls checklist access and visibility.
    │   │
    │   └── TaskInstancePolicy.php
    │       # Controls claim, release, complete, skip and cancel actions.
    │
    ├── Services
    │   ├── WorkEngine.php
    │   │   # Central workflow orchestration service.
    │   │
    │   ├── ChecklistProgressService.php
    │   │   # Calculates checklist completion state.
    │   │
    │   ├── TaskAssignmentService.php
    │   │   # Handles claiming, assignment and release logic.
    │   │
    │   └── WorkMetricsService.php
    │       # Produces workflow statistics and KPI calculations.
    │
    ├── Http
    │   ├── API
    │   │   └── Resources
    │   │       ├── WorkPackageResource.php
    │   │       │   # JSON representation of WorkPackage.
    │   │       │
    │   │       ├── ChecklistResource.php
    │   │       │   # JSON representation of Checklist.
    │   │       │
    │   │       ├── TaskResource.php
    │   │       │   # JSON representation of Task.
    │   │       │
    │   │       ├── WorkPackageInstanceResource.php
    │   │       │   # JSON representation of workflow execution.
    │   │       │
    │   │       ├── ChecklistInstanceResource.php
    │   │       │   # JSON representation of checklist execution.
    │   │       │
    │   │       └── TaskInstanceResource.php
    │   │           # JSON representation of task execution.
    │   │
    │   └── UI
    │
    │       ├── Admin
    │       │   ├── Resources
    │       │   │   ├── WorkPackageResource.php
    │       │   │   │   # Manage WorkPackages.
    │       │   │   │
    │       │   │   ├── ChecklistResource.php
    │       │   │   │   # Manage Checklists.
    │       │   │   │
    │       │   │   ├── TaskResource.php
    │       │   │   │   # Manage Tasks.
    │       │   │   │
    │       │   │   └── WorkPackageInstanceResource.php
    │       │   │       # Monitor workflow executions.
    │       │   │
    │       │   ├── Pages
    │       │   │   ├── Dashboard.php
    │       │   │   │   # Workflow administration dashboard.
    │       │   │   │
    │       │   │   └── WorkPackageBoard.php
    │       │   │       # Kanban/timeline style execution board.
    │       │   │
    │       │   └── Widgets
    │       │       ├── ActiveWorkPackagesWidget.php
    │       │       │   # Active workflow count.
    │       │       │
    │       │       ├── ActiveTasksWidget.php
    │       │       │   # Active task count.
    │       │       │
    │       │       └── CompletedWorkPackagesWidget.php
    │       │           # Completed workflow statistics.
    │       │
    │       └── Staff
    │           ├── Resources
    │           │   ├── MyTaskResource.php
    │           │   │   # Staff's assigned tasks.
    │           │   │
    │           │   ├── QueueTaskResource.php
    │           │   │   # Available queue tasks matching user's role.
    │           │   │
    │           │   └── MyWorkPackageResource.php
    │           │       # WorkPackages relevant to the user.
    │           │
    │           ├── Pages
    │           │   ├── MyTasks.php
    │           │   │   # Staff task inbox.
    │           │   │
    │           │   ├── MyWorkPackages.php
    │           │   │   # Staff workflow tracking page.
    │           │   │
    │           │   └── TaskQueue.php
    │           │       # Role-based queue task pickup page.
    │           │
    │           └── Widgets
    │               ├── MyAssignedTasksWidget.php
    │               │   # Assigned task summary.
    │               │
    │               ├── QueueTasksWidget.php
    │               │   # Available queue task summary.
    │               │
    │               └── PendingChecklistsWidget.php
    │                   # Active checklist progress summary.
    │
    └── Enums
        ├── WorkPackageStatus.php
        │   # active, completed, cancelled
        │
        ├── ChecklistStatus.php
        │   # pending, completed, cancelled
        │
        └── TaskStatus.php
            # queue, active, completed, skipped, cancelled
```

Admin CRUD WorkPackages.  
Admin CRUD Checklists and Tasks for their OrgTeam.  
User starts WorkPackageInstance.  
System creates WorkPackageInstance(status=active).  
System creates first ChecklistInstance(status=pending).  
System creates TaskInstances(status=queue).  
User can view queue TaskInstances matching their Role.  
User can claim queue TaskInstances.  
System updates TaskInstance(status=active) and assignedTo.  
User can release assigned TaskInstances back to queue.  
User can complete assigned TaskInstances.  
Supervisor can skip or cancel TaskInstances.  
System marks ChecklistInstance(status=completed) when all TaskInstances are completed, skipped, or cancelled.  
System creates the next ChecklistInstance(status=pending) when the current ChecklistInstance is completed.  
System marks WorkPackageInstance(status=completed) when all ChecklistInstances are completed.  
System records all state transitions in AuditLog.  



