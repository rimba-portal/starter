# Workflow Engine Architecture: Design-Time vs. Runtime

This document details the complete architecture of an instance-driven Workflow Engine featuring dynamic Nodes, Checklists, and Parallel Tasks built with Laravel and Filament.

---

## 📂 The Complete Architecture File Map

```text
app/
├── Models/
│   ├── WorkflowBlueprint.php
│   ├── WorkflowNode.php
│   ├── WorkflowTransition.php
│   ├── Workpackage.php
│   ├── Checklist.php
│   ├── Task.php
│   ├── WorkflowInstance.php
│   ├── WorkflowNodeInstance.php
│   └── WorkflowTaskInstance.php
├── Services/
│   └── WorkflowEngineService.php
└── Filament/
    └── Resources/
        ├── WorkflowBlueprintResource.php
        └── [YourOperationalEntity]Resource.php
```

---

## 🛠️ Detailed File Breakdown

### 1. The Design-Time Framework (The Configuration Blueprint)

These models allow business operations managers to draw the maps, define the checklists, and assign the parallel operational requirements. No actual work happens here; this is purely the "template master plan."

#### `Models/WorkflowBlueprint.php`
*   **For Developers:** The root model of the graph structure. It holds a `hasMany` relationship to `WorkflowNode` and serves as the primary target lookup key when spinning up a new workflow instance.
*   **For Business Owners:** This is the master title of your corporate process blueprint (e.g., *"Capital Expenditure Approval Over \$10k"*, *"New Employee Onboarding"*). 

#### `Models/WorkflowNode.php`
*   **For Developers:** Represents a single state or coordinate vertex inside the workflow graph. Contains fields like `type` (`start`, `standard`, `end`) and handles the `hasOne` relationship to a configuration `Workpackage`.
*   **For Business Owners:** A specific milestone, seat, or department desk where the record sits (e.g., *"Manager Review Step"*, *"Compliance Audit Desk"*).

#### `Models/WorkflowTransition.php`
*   **For Developers:** Connects nodes using an explicit Directed Graph schema. It defines directional movement by storing foreign keys for both a `from_node_id` and a `to_node_id`.
*   **For Business Owners:** The rules, roads, and action outcomes that connect one desk to another (e.g., an *"Approve"* pathway routes it forward; a *"Reject & Return"* pathway loops it back).

#### `Models/Workpackage.php`
*   **For Developers:** A structural bridge table providing a strict `1:1` relational anchor between a `WorkflowNode` and its underlying `Checklist` chains. 
*   **For Business Owners:** The bundle of responsibilities bound to a specific milestone. It says: *"To leave this desk, you must complete this specific bundle of work."*

#### `Models/Checklist.php`
*   **For Developers:** A sequential structural model. It implements an integer `sort_order` or utilizes an ordering trait to ensure records must be evaluated step-by-step (`Checklist 1` must clear before `Checklist 2` initializes).
*   **For Business Owners:** Sequential stages within a desk. For example, a credit check must be finished *before* a manager signs off on contract terms.

#### `Models/Task.php`
*   **For Developers:** The lowest granular level of the blueprint template. Holds text fields for instructions/descriptions. Tasks nested under the same checklist parent are structural peers (processed in parallel).
*   **For Business Owners:** A singular, actionable to-do item (e.g., *"Upload ID scan"*, *"Verify banking details"*). These can be checked off in any order by team members at that specific desk.

---

### 2. The Runtime Framework (The Live Execution Instances)

These models handle the live production state. Every time a new invoice, claim, or ticket enters the system, a snapshot of the blueprint is instantiated here to track live progress without risking template corruption.

#### `Models/WorkflowInstance.php`
*   **For Developers:** Relies on a polymorphic Eloquent relationship (`morphs('trackable')`). This lets you attach the same workflow engine mechanics to an `Invoice`, an `ExpenseClaim`, or a `Project` table seamlessly.
*   **For Business Owners:** An active, running ticket tracking a real-world document. It knows exactly when a specific client file entered the system and its overarching processing status (*Active*, *Stalled*, *Approved*).

#### `Models/WorkflowNodeInstance.php`
*   **For Developers:** Tracks state context for the active node position. Records specific timestamps like `activated_at` and `completed_at` to accurately measure historical time intervals spent on each vertex.
*   **For Business Owners:** An audit trail showing exactly which desk currently holds a ticket, who is responsible for it, and how many days it has been stuck sitting on that specific milestone.

#### `Models/WorkflowTaskInstance.php`
*   **For Developers:** Holds mutable columns such as `is_completed` (boolean), `completed_by_user_id`, and `completed_at`. This decouples live state modifications entirely from the blueprint template records.
*   **For Business Owners:** The actual signature log. It answers exactly: *"Who checked off task box #3, and at what precise date and time did they do it?"*

---

### 3. The Backend Engine & Frontend Interfaces

The functional code that transitions state machines from blueprints to live tracking interfaces inside your dashboard.

#### `Services/WorkflowEngineService.php`
*   **For Developers:** A non-visual, isolated backend service utility class. It features an automated `instantiate()` function to replicate blueprint structures into instance states, along with a `checkCompletionAndTransition()` hook evaluating your automation rules.
*   **For Business Owners:** The invisible operational logic engine. It is the rulebook that automatically detects when the last task is finished and moves the electronic document to the next person's desk instantly, bypassing human lag.

#### `Filament/Resources/WorkflowBlueprintResource.php`
*   **For Developers:** A Filament administration Resource utilizing a nested structure of components (`Section` \(\rightarrow\) `Repeater` \(\rightarrow\) `Repeater`) to gracefully parse and save multi-tier data patterns in a clean admin form layout.
*   **For Business Owners:** The visual Management Studio. This is the simple administration interface where operations executives can build new processes, add checklist items, or change routing configurations without needing a software engineer to write code.

#### `Filament/Resources/[YourOperationalEntity]Resource.php`
*   **For Developers:** The primary end-user operational workspace (e.g., `ExpenseClaimResource.php`). It queries the live `WorkflowTaskInstance` relation to draw custom action buttons and display toggle inputs dynamically.
*   **For Business Owners:** The clear application screen your staff looks at every day. It shows them the active document details, lists the checkboxes they need to mark complete, and displays the contextual transition choice buttons when their tasks are finished.
