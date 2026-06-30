```text
Rimba
└── Trees/
  └── <Tree name>/                  # Package name
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
package: rimba/tree/flow
dependencies: [rimba/tree/work]

namespace Rimba\Tree\Flow\Models;

models:

  WorkflowBlueprint:
    name: string
    owner: foreign nullable
    active: boolean default:true
    relationships:
      belongsTo: OrgTeam:owner
      belongsToMany: Role:requesterRoles
      hasMany: WorkflowNode

  WorkflowNode:
    workflow_blueprint_id: foreign
    workpackage_id: foreign
    name: string
    type: string index
    relationships:
      belongsTo: WorkflowBlueprint, WorkPackage
      hasMany: OutgoingTransition:WorkflowTransition
      hasMany: IncomingTransition:WorkflowTransition

  WorkflowTransition:
    workflow_blueprint_id: foreign
    from_node_id: foreign
    to_node_id: foreign
    name: string nullable
    condition: text nullable
    action: string nullable
    relationships:
      belongsTo:
        - WorkflowBlueprint
        - WorkflowNode:fromNode
        - WorkflowNode:toNode

  WorkflowInstance:
    workflow_blueprint_id: foreign
    trackable_id: nullable
    trackable_type: nullable
    status: string default:'active'
    relationships:
      belongsTo: WorkflowBlueprint
      morphTo: trackable
      hasMany:
        - WorkflowNodeInstance
        - WorkflowTransitionInstance

  WorkflowNodeInstance:
    workflow_instance_id: foreign
    workflow_node_id: foreign
    activated_at: timestamp
    completed_at: timestamp nullable
    relationships:
      belongsTo:
        - WorkflowInstance
        - WorkflowNode

  WorkflowTransitionInstance:
    workflow_instance_id: foreign
    workflow_transition_id: foreign
    executed_at: timestamp
    executed_by_id: nullable foreign users
    relationships:
      belongsTo:
        - WorkflowInstance
        - WorkflowTransition
```

```text
rimba/tree/flow
├── composer.json
├── README.md
├── config
│   └── flow.php
├── database
│   └── migrations
│       ├── create_workflow_blueprints_table.php
│       ├── create_workflow_nodes_table.php
│       ├── create_workflow_transitions_table.php
│       ├── create_workflow_instances_table.php
│       ├── create_workflow_node_instances_table.php
│       └── create_workflow_transition_instances_table.php
├── resources
│   ├── views
│   ├── lang
│   └── icons
└── src
    ├── FlowServiceProvider.php
    │   # Registers migrations, config, events, listeners and policies.
    │
    ├── Actions
    │   ├── CreateWorkflowBlueprint.php
    │   │   # Creates a reusable workflow blueprint.
    │   │
    │   ├── AddWorkflowNode.php
    │   │   # Adds a node to a blueprint.
    │   │
    │   ├── AddWorkflowTransition.php
    │   │   # Connects two nodes together.
    │   │
    │   ├── StartWorkflow.php
    │   │   # Starts a WorkflowInstance.
    │   │
    │   ├── ActivateNode.php
    │   │   # Activates a WorkflowNodeInstance.
    │   │
    │   ├── ExecuteTransition.php
    │   │   # Executes a transition between nodes.
    │   │
    │   ├── CompleteNode.php
    │   │   # Completes a node when its WorkPackageInstance completes.
    │   │
    │   ├── CancelWorkflow.php
    │   │   # Cancels an active workflow.
    │   │
    │   └── CompleteWorkflow.php
    │       # Marks workflow completed.
    │
    ├── Builders
    │   ├── WorkflowBlueprintBuilder.php
    │   │   # Query scopes for blueprints.
    │   │
    │   ├── WorkflowNodeBuilder.php
    │   │   # Query scopes for nodes.
    │   │
    │   ├── WorkflowTransitionBuilder.php
    │   │   # Query scopes for transitions.
    │   │
    │   ├── WorkflowInstanceBuilder.php
    │   │   # Query scopes for executions.
    │   │
    │   ├── WorkflowNodeInstanceBuilder.php
    │   │   # Query scopes for node executions.
    │   │
    │   └── WorkflowTransitionInstanceBuilder.php
    │       # Query scopes for executed transitions.
    │
    ├── Events
    │   ├── WorkflowStarted.php
    │   │   # Workflow execution created.
    │   │
    │   ├── WorkflowNodeActivated.php
    │   │   # Node became active.
    │   │
    │   ├── WorkflowNodeCompleted.php
    │   │   # Node completed.
    │   │
    │   ├── WorkflowTransitionExecuted.php
    │   │   # Transition executed.
    │   │
    │   ├── WorkflowCancelled.php
    │   │   # Workflow cancelled.
    │   │
    │   └── WorkflowCompleted.php
    │       # Workflow completed.
    │
    ├── Listeners
    │   ├── CreateFirstNode.php
    │   │   # Creates first node instance after workflow start.
    │   │
    │   ├── CreateWorkPackageInstance.php
    │   │   # Starts WorkPackage for active node.
    │   │
    │   ├── CompleteNodeWhenWorkPackageCompletes.php
    │   │   # Listens for WorkPackageCompleted event.
    │   │
    │   ├── EvaluateTransitions.php
    │   │   # Evaluates node exit conditions.
    │   │
    │   ├── ExecuteNextTransitions.php
    │   │   # Executes matching transitions.
    │   │
    │   ├── ActivateNextNodes.php
    │   │   # Creates next node instances.
    │   │
    │   ├── AutoCompleteWorkflow.php
    │   │   # Completes workflow when no active nodes remain.
    │   │
    │   └── WriteAuditLog.php
    │       # Writes all significant activities to Trail.
    │
    ├── Models
    │   ├── WorkflowBlueprint.php
    │   │   # Workflow template.
    │   │
    │   ├── WorkflowNode.php
    │   │   # A workflow step referencing a WorkPackage.
    │   │
    │   ├── WorkflowTransition.php
    │   │   # Connection between two nodes.
    │   │
    │   ├── WorkflowInstance.php
    │   │   # Runtime workflow execution.
    │   │
    │   ├── WorkflowNodeInstance.php
    │   │   # Runtime node execution.
    │   │
    │   └── WorkflowTransitionInstance.php
    │       # Executed transition record.
    │
    ├── Observers
    │   ├── WorkflowBlueprintObserver.php
    │   │   # Blueprint lifecycle.
    │   │
    │   ├── WorkflowNodeObserver.php
    │   │   # Node lifecycle.
    │   │
    │   ├── WorkflowTransitionObserver.php
    │   │   # Transition lifecycle.
    │   │
    │   ├── WorkflowInstanceObserver.php
    │   │   # Execution lifecycle.
    │   │
    │   ├── WorkflowNodeInstanceObserver.php
    │   │   # Node execution lifecycle.
    │   │
    │   └── WorkflowTransitionInstanceObserver.php
    │       # Transition execution lifecycle.
    │
    ├── Policies
    │   ├── WorkflowBlueprintPolicy.php
    │   │   # Blueprint CRUD authorization.
    │   │
    │   ├── WorkflowNodePolicy.php
    │   │   # Node management authorization.
    │   │
    │   ├── WorkflowTransitionPolicy.php
    │   │   # Transition management authorization.
    │   │
    │   ├── WorkflowInstancePolicy.php
    │   │   # Workflow execution authorization.
    │   │
    │   ├── WorkflowNodeInstancePolicy.php
    │   │   # Node execution authorization.
    │   │
    │   └── WorkflowTransitionInstancePolicy.php
    │       # Transition execution authorization.
    │
    ├── Services
    │   ├── WorkflowEngine.php
    │   │   # Orchestrates workflow execution.
    │   │
    │   ├── TransitionEvaluator.php
    │   │   # Evaluates conditions.
    │   │
    │   ├── NodeActivationService.php
    │   │   # Activates downstream nodes.
    │   │
    │   ├── WorkflowGraphService.php
    │   │   # Graph traversal utilities.
    │   │
    │   └── WorkflowMetricsService.php
    │       # Reporting and analytics.
    │
    ├── Http
    │   ├── API
    │   │   └── Resources
    │   │       ├── WorkflowBlueprintResource.php
    │   │       ├── WorkflowNodeResource.php
    │   │       ├── WorkflowTransitionResource.php
    │   │       ├── WorkflowInstanceResource.php
    │   │       ├── WorkflowNodeInstanceResource.php
    │   │       └── WorkflowTransitionInstanceResource.php
    │   │
    │   └── UI
    │
    │       ├── Admin
    │       │   ├── Resources
    │       │   │   ├── WorkflowBlueprintResource.php
    │       │   │   ├── WorkflowNodeResource.php
    │       │   │   ├── WorkflowTransitionResource.php
    │       │   │   └── WorkflowInstanceResource.php
    │       │   │
    │       │   ├── Pages
    │       │   │   ├── Dashboard.php
    │       │   │   └── WorkflowDesigner.php
    │       │   │       # Visual node editor.
    │       │   │
    │       │   └── Widgets
    │       │       ├── ActiveWorkflowsWidget.php
    │       │       ├── ActiveNodesWidget.php
    │       │       └── CompletedWorkflowsWidget.php
    │       │
    │       └── Staff
    │           ├── Resources
    │           │   └── MyWorkflowResource.php
    │           │
    │           ├── Pages
    │           │   └── MyWorkflows.php
    │           │
    │           └── Widgets
    │               ├── ActiveWorkflowWidget.php
    │               └── PendingNodeWidget.php
    │
    └── Enums
        ├── WorkflowStatus.php
        │   # active, completed, cancelled
        │
        ├── WorkflowNodeType.php
        │   # start, workpackage, decision, merge, end
        │
        ├── WorkflowNodeStatus.php
        │   # active, completed, cancelled
        │
        └── WorkflowTransitionStatus.php
            # executed
```
### Admin Responsibilities
Admin can CRUD WorkflowBlueprints.
Admin can CRUD WorkflowNodes.
Admin can CRUD WorkflowTransitions.
Admin can connect WorkflowNodes using WorkflowTransitions.
Admin can define transition conditions.
Admin can activate or deactivate WorkflowBlueprints.
Admin can start WorkflowInstances.
Admin can monitor WorkflowInstances.
Admin can cancel WorkflowInstances.

### User Responsibilities
User can view active WorkflowInstances assigned to them.
User can view current WorkflowNodeInstances.
User can execute Tasks belonging to WorkPackages.
User can claim queue TaskInstances.
User can release assigned TaskInstances.
User can complete assigned TaskInstances.
User can view workflow progress.

### Starting Workflow
System creates WorkflowInstance(status=active).
System identifies Start Node.
System creates first WorkflowNodeInstance.
System starts associated WorkPackageInstance.

### WorkPackage Progression
System monitors WorkPackageCompleted events.
System marks WorkflowNodeInstance completed.
System evaluates outgoing WorkflowTransitions.
System executes valid transitions.
System creates next WorkflowNodeInstances.
System starts corresponding WorkPackageInstances.

### Workflow Completion
System marks WorkflowInstance completed when no further nodes exist.
System records completion timestamps.
System writes AuditLog entries.