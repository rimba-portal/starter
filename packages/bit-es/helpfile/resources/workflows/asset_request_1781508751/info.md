# Organization (Foundation Overview)<a id="organization"></a>

The **Organization** module defines how a company is structured, who the people are, and how work is assigned.

It ensures that the system clearly understands:

- Where people belong
- What roles exist
- Who is responsible for what


## Why This Matters

In any company, especially manufacturing:

- People move between roles
- Teams change over time
- Responsibilities shift
- Contracts start and end

This module ensures all these changes are handled **clearly and consistently**, without breaking the system.


## Key Components

### 1. Organizational Structure (Org)<a id="org"></a>

This defines how the company is arranged.

- **Org Corp** → The company itself + any other interacting entities (businesses, governments, institutions)
- **Org Unit** → Departments (e.g., Production, Finance) within the company.
- **Org Team** → A grouping with responsibility to a service offering, documents, and/or learnings within the company.

✅ Focus: _Structure only (no people inside yet)_


### 2. Work & Responsibilities (Job)<a id="job"></a>

This defines what work exists and how it is assigned.

- **Job Contract** → Definitions of the workforce needed to deliver the works, [**Org Team**](#org) responsibility and [**Org Unit**](#org) accountability
- **Job Position** → Official Job title, description linked to **Job Contract** and list of **Job Role**
- **Job Role** → Responsibilities (e.g., Approve Offering, Create Document, Educate People)

✅ Focus: _What needs to be done, not who does it_


### 3. People (Person)<a id="person"></a>

This represents individuals in the system.

- **User** → Login account, the actual person
- **Staff** → Identity and attibutes of a person in relation to the [**Org Unit**](#org) and [**Job Contract**](#job)
- **Employee** → Attributes of a staff under the direct payroll of the company. Will have an employment contract with [**Org Corp**](#org)

✅ Focus: _Who the person is (name, details, identity)_  
❌ Does NOT define their job or role


## How It Works Together

Instead of mixing everything, the system links them:

- A **Staff** is assigned to a [**Job Position**](#job) as (1-1)
- **Job Position** can be assigned to a **Job Contract** (1-N, limit of N defined in **Job Contract**)
- A [**Job Contract**](#job) belongs to a **Org Unit / Org Team**
- A [**Job Role**](#job) defines the responsibilities


## Example (Simple Scenario)

- Department: Maintenance
- Team: Electrical
- Position: Technician
- Person: Ahmad

Ahmad is:

- Assigned to the _Technician position_
- Working in the _Electrical team_
- Given a _role_ (e.g., execute tasks)
- Assigned for a _specific time period_


## Flexibility (Important for Business)

This design allows the business to:

- Move people between teams easily (change of [**Job Contract**](#job))
- Assign multiple roles to one person (As per [**Job Position**](#job))
- Track history of roles and assignments
- Support temporary or contract workers
- Adapt to organizational changes without system redesign


## Custom Attributes

Each part (Org, Person, Job) can store additional business-specific information such as:

- Skill level
- Certification
- Department type
- Cost center

✅ No need to change system structure for new requirements


## Access & Permissions

What a person can do in the system is based on:

- Their **role**
- Their **attributes**
- Their **position in the organization**

This ensures proper:

- Approval control
- Segregation of duties
- Security compliance


## Summary

The Organization module ensures:

- Clear separation between **structure, people, and work**
- Flexible assignment of people to roles
- Easy handling of changes over time
- Strong foundation for business processes


## In Simple Terms

Think of it like:

- **Org** → The company structure (boxes)
- **Person** → The people (names)
- **Job** → The responsibilities (what needs to be done)

And the system connects them in a clean, flexible way.

# Authorization (Foundation Overview)<a id="authorization"></a>

The **Authorization module** controls **what users are allowed to see and do** in the system.

It ensures that:

- The right people have the right access
- Sensitive actions are properly controlled
- Responsibilities follow organizational roles

## Why This Matters

In any organization:

- Not everyone should see all data
- Not everyone can approve or perform actions
- Responsibilities differ by job, team, and department
- Compliance requires proper control and traceability

This module ensures:

✅ Only authorized users can perform actions  
✅ Access is based on roles and responsibilities  
✅ Segregation of duties is enforced  
✅ The system remains secure and compliant

## Key Concept

Authorization answers the question:

> **“What can this person do in the system?”**

**Role**: A key given to the person.  
**Permission**: Which gate can be opened by the key.  
**Gate**: At a checkpoint there can be several gates. A gate is opened, gives you certain access  
**Policy**: A definition of which key can open which gate. And what access is given to the gate when opened.

## How Access is Determined

Access(Permission) is not given randomly. **Permissions** are given to **Roles**.

Roles derived from:

### 1. ABAC – Attribute-Based Access Control<a id="abac"></a>

Roles based from attributes such as:

- [Org Corp](#org) (which company)
- [Org Unit](#org)
- [Org Team](#org)
- [Job Position](#job)
- [Job Role](#job)
- [Contract type](#job)

✅ Example:

- A user/staff in **Maintenance Department** is given the department member role
- A user/staff with Job Role **Supervisor** is given the supervisor role

### 2. RBAC – Role-Based Access Control<a id="rbac"></a>

Roles represent responsibilities.  
The roles are assigned to the staff by the owner of [**Org Team**](#org) for team roles and owner of [**Org Unit**](#org)  
Each **Org Unit** will have at least owner and member role,  
and **Org Team** will have scout, captain, player, quartermaster, tactician and coach
| Example |
|------|
|d.<span style="color:green">_OrgUnit_name_</span>.owner |
|d.<span style="color:green">_OrgUnit_name_</span>.member |
|t.<span style="color:green">_OrgTeam_name_</span>.scout |
|t.<span style="color:green">_OrgTeam_name_</span>.captain |
|t.<span style="color:green">_OrgTeam_name_</span>.player |
|t.<span style="color:green">_OrgTeam_name_</span>.quartermaster |
|t.<span style="color:green">_OrgTeam_name_</span>.tactician |
|t.<span style="color:green">_OrgTeam_name_</span>.coach |
||

✅ Roles (set by owners) are automatically removed for a user/staff, if [**ABAC**](#abac) for OrgUnit and/or OrgTeam.

### 3. Permission

Permission based on policy to an object (a view, a resource, or any) is set with a "gate". The gate allows

- Create → an object
- Read / View → specific object
- Read / ViewAny → see all of objects
- Update → change or edit object
- Delete → remove the object

✅ This allows:

- Flexible and dynamic access control
- Less manual role management
- Better alignment with real business structure

## Role Structure (Based on Domain)

Roles are linked to different parts of the system using prefixes:

| Prefix | Meaning              | Example           |
| ------ | -------------------- | ----------------- |
| o.     | OrgCorp              | o.atm             |
| d.     | OrgUnit (Department) | d.facility.member |
| t.     | OrgTeam              | t.safety.player   |
| u.     | User                 | u.visitor         |
| s.     | Staff                | s.shift           |
| e.     | Employee             | e.jobgrade        |
| p.     | JobPosition          | p.technician      |
| r.     | JobRole              | r.recruiter       |
| c.     | JobContract          | c.ftc             |
|        |                      |

✅ This ensures roles are clearly linked to business context

## How It Works (Simple Flow)

### Continuous staff attribute updates

1. A sync with staff database occurs
2. The system identifies the linked **Staff**
3. Staff is assigned to:
    - [Job Position](#job)
    - [Job Role](#job)
    - [**Org Unit** / **Org Team**](#org)
4. Attributes are evaluated
5. StaffRole created and maintained

### User Login

1. User is attached to Staff either automatically or manually
2. Roles are applied based on StaffRole
3. Permissions are enforced

## Example (Simple Scenario)

- Person: Ahmad
- Department: Maintenance
- Role: Technician

Result:

- Ahmad can:
    - View maintenance tasks
    - Perform assigned work
- Ahmad cannot:
    - Approve budgets
    - Modify company settings

## Segregation of Duties (Important for Compliance)

The system ensures that:

- A person who **creates** cannot always **approve**
- Critical actions require proper authority
- Conflicts of interest are minimized

✅ Important for audits and governance

## Integration with Other Modules

- [**Org**](#org) → Defines structure (department, team)
- [**Person**](#org) → Identifies the user
- [**Job**](#org) → Defines roles and responsibilities
- [**Workflow**](#workflow) → Controls approval authority
- [**Audit**](#log) → Tracks all access and actions

## Flexibility for Business

The system allows:

- Automatic role generation from attributes
- Manual role assignment where needed
- Fine-grained access control
- Multi-company and multi-site access rules

## Audit & Traceability

All access and actions are tracked:

- Who performed an action
- What action was performed
- When it happened

✅ Supports compliance and investigations

## Summary

The Authorization module ensures that:

- Access is controlled based on real business structure
- Responsibilities are clearly enforced
- Security and compliance are maintained
- The system adapts to organizational changes

## In Simple Terms

Think of it like:

- **Attributes** → Who you are and where you belong
- **Role** → What you are allowed to do

And the system ensures that **everyone can only do what they are supposed to do**.

# Audit Trail (Overview)<a id="audit-trail"></a>

The **Audit Trail module** provides a centralized and authoritative record of all important actions performed across the system.

It captures:

- Who performed an action
- What action was taken
- Which record was affected
- Whether the action was allowed or denied
- Why the decision was made

This module applies to **all system modules** including Version Management, Workflow, Authorization, Documents, Tasks, and more.


## Why This Matters

In daily operations:

- Sensitive actions must be traceable
- Decisions must be accountable
- Unauthorized attempts must be visible
- Compliance requires historical evidence

Without a centralized audit trail:

- Responsibility becomes unclear
- Security incidents are hard to investigate
- Changes cannot be reliably reconstructed
- Compliance and governance are weakened

This module ensures the organization always knows:

✅ Who did what  
✅ To which record  
✅ With what outcome  
✅ And for what reason


## Key Objectives

The Audit Trail module helps to:

- Centralize auditing across all business modules
- Provide consistent and readable action logs
- Support security reviews and investigations
- Enable compliance and governance reporting
- Preserve an immutable history of actions


## Key Components

### 1. Audit Log

The **Audit Log** is the single audit table used by the entire system.

✅ It is the **only audit table**  
✅ All modules write to it  
✅ Records are append‑only

Each log entry represents **one system action**.


### 2. Actor

Represents **who performed the action**.

An actor may be:

- A system user
- A staff member
- Or both

✅ Supports separation between system identity and business identity


### 3. Action

Describes **what was attempted or performed**.

Examples:

- `document.created`
- `version.approved`
- `version.published`
- `task.deleted`
- `authorization.denied`

✅ Uses a consistent, readable naming convention


### 4. Target Record

Identifies **which record was affected**.

✅ Works with any model  
✅ Does not require tight coupling

Allows auditing across:

- Documents
- Versions
- Tasks
- Workflow items
- Configuration records


### 5. Result

Indicates the outcome of the action.

Possible values:

- `allowed`
- `denied`

✅ Both successful and rejected actions are recorded


### 6. Reason

Optional explanation describing **why the action succeeded or failed**.

Examples:

- Insufficient permissions
- Version not approved
- Policy restriction

✅ Especially important for denied actions


### 7. Metadata

Additional contextual information stored as structured data.

Examples:

- Old and new values
- Workflow step identifiers
- Request source (UI, API)
- Related record references

✅ Flexible and extensible without schema changes


## How It Works Together

The system records an audit entry whenever:

- A user attempts an action
- Authorization rules are evaluated
- Workflow decisions are made
- A version is approved or published
- A record is created, updated, or deleted

✅ This behavior is consistent across **all modules**


## Example (Simple Scenario)

### Scenario: Version Approval

- User: Manager
- Action: Approve document version

Audit entry records:

- Action → `version.approved`
- Target → Version record
- Result → allowed
- Reason → Approval granted


### Scenario: Unauthorized Delete

- User: Staff member
- Action: Delete document

Audit entry records:

- Action → `document.deleted`
- Result → denied
- Reason → Insufficient permission


## Integration with Other Modules

- **Version Management** → Draft, approve, publish actions
- **Workflow** → Approvals, rejections, escalations
- **Authorization** → Access checks and denials
- **Document Management** → Lifecycle tracking
- **Task / Process** → Operational decisions
- **Security** → Incident investigation

✅ All modules rely on the same audit mechanism


## Compliance & Governance

The Audit Trail module supports:

- Internal and external audits
- Regulatory compliance
- Incident analysis
- Historical reconstruction of events

✅ Designed for long‑term retention and integrity


## Summary

The Audit Trail module ensures that:

- Every important action is recorded
- Responsibility is always traceable
- Denied actions are visible and explainable
- All modules follow a unified audit standard
- The system remains transparent and compliant


## In Simple Terms

Think of it like:

- **Actor** → Who acted
- **Action** → What they did
- **Record** → What they acted on
- **Result** → Allowed or denied
- **Reason** → Why it happened

And the system keeps this record **forever**, for **every important action**, across **the entire platform**.

# Version (Overview)<a id="version"></a>

The **Version module** controls how business records, documents, and configurations evolve over time.

It provides a structured way to:

- Track changes
- Manage approvals
- Control what is officially published
- Preserve historical records

✅ This module is generic and reusable — it can be applied to any model that requires controlled versioning, not just documents.

## Why This Matters

In daily operations:

- Documents change over time
- Updates need review before release
- Only approved information should be visible or effective
- Historical versions must remain traceable

Without proper version control:

- Incorrect data may be used
- Changes can bypass approval
- Audit trails are lost
- Rollbacks become difficult

This module ensures the organization always knows:

✅ What version is current
✅ What is still a draft
✅ What has been approved
✅ What is officially published

## Key Objectives

The Version Management module helps to:

- Maintain controlled change history
- Enforce approval before publication
- Support semantic versioning (Major / Minor / Patch)
- Preserve immutable historical records
- Improve governance and compliance

## Key Concepts

### 1. Versionable Entity

Any business record that can evolve over time.

Examples:

- Document
- Learning Materials
- Workflow Configuration
- Service Catalog / Menu
- Schema or template

✅ The base entity represents identity and metadata, not content.

### 2. Version

Represents a specific state of the entity at a point in time.

Each version includes:

- Semantic version number (e.g. 1.2.0)
- Snapshot of metadata
- Content reference (file or external link)
- Status (Draft, Approved, Published)
- Change notes
- Creator and timestamp

✅ Versions are immutable once published.

### 3. Menu Visibility Flag (Optional, Contextual)

Some versioned entities (such as **Service Catalog items**) need to control **whether a version is visible to end users**.

For this purpose, Version includes an optional boolean flag:

is_menu = true / false

✅ Meaning:

- `true` → Version is visible in a menu (e.g. Service Catalog)
- `false` → Version is hidden from menus

✅ Rules:

- Only **Published** versions may appear in menus
- Draft or Approved versions are never shown, regardless of this flag

✅ This allows:

- Safe preparation of new service definitions
- Controlled rollout of changes
- Retirement of services without deleting history

✅ For non-menu use cases, this flag is ignored.


### 4. Semantic Versioning

Each version follows: MAJOR.MINOR.PATCH

Examples:

- Major → Breaking or structural change
- Minor → Content or feature update
- Patch → Small fix or correction

✅ This clearly communicates the impact of changes.

### 5. Version Status Lifecycle

Each version moves through a controlled lifecycle:

Draft → Approved → Published → Archived

Optional paths:

Draft → Rejected

✅ Prevents unreviewed changes from going live.

### 6.Version Status Explained

Draft

- Work in progress
- Editable
- Not visible to end users

✅ Created by editors or contributors

Approved

- Reviewed and accepted
- Ready for release
- Cannot be modified

✅ Approved by reviewer or manager

Published

- Official active version
- Used by the system or users
- Only one published version at a time

✅ Represents the “live” state

Archived (Optional)

- Previously published version
- Kept for audit and rollback
- Read-only

## How It Works Together

The system enforces a clear flow:

- User creates or updates content
- A new version is created as Draft
- Draft is reviewed and Approved
- Approved version is Published
- Previous published version is archived

✅ Ensures controlled and traceable change management

## Integration with Other Modules

- **Workflow** → Approval and rejection flows
- **Audit** → Change tracking and compliance
- **Task** → Work items tied to version changes
- **Org** / **Person** → Role-based access control
- **Document** Management → File storage per version

## Flexibility for Business

The system supports:

- Different approval rules for Major vs Minor changes
- Multiple versionable entities
- External links or file-based content
- Optional rejection and rework cycles
- Future automation (auto-approval, escalation)

## Custom Attributes

Versions can store additional metadata such as:

- Effective date
- Applicable department
- Change category
- Risk level
- Compliance reference

✅ Easily extensible per business needs

## Summary

The Version Management module ensures that:

- Changes are intentional and controlled
- Only approved information is published
- Historical versions are preserved
- Semantic versioning communicates impact
- Governance and audit requirements are met

## In Simple Terms

Think of it like:

- **Document** → The identity
- **Version** → The state
- Draft → Work in progress
- Approved → Signed off
- Published → Live and official

And the system ensures only the right version is used at the right time, with full traceability.

# Task Management (Platform Overview)<a id="task"></a>

The **Task module** manages work that needs to be done by people in the organization.

It ensures that:

- Work is clearly assigned
- Responsibilities are tracked
- Nothing is missed
- Progress is visible


## Why This Matters

In daily operations:

- Tasks are often communicated informally (calls, messages)
- Work can be forgotten or delayed
- Responsibilities may be unclear
- No proper tracking or accountability

This module ensures the organization always knows:

✅ What needs to be done  
✅ Who is responsible  
✅ When it must be completed  
✅ What is the current status


## Key Objectives

The Task module helps to:

- Assign work clearly to individuals or roles
- Track progress from start to completion
- Ensure accountability
- Support operational workflows
- Improve efficiency and coordination


## Key Components

### 1. Task

Represents a piece of work to be completed.

Examples:

- Inspect a machine
- Approve a document
- Conduct training
- Fix equipment

✅ Each task includes:

- Title
- Description
- Assignee (staff)
- Assignment (role)
- Due date
- Status


### 2. Task List

A **Task List** is contains a **group (list) of related tasks**.

It is used when multiple tasks need to be performed together as part of a process.

Examples:

- Machine Maintenance Package
- New Employee Onboarding
- Document Review Cycle
- Safety Inspection Routine

✅ Each Task List includes:

- A list of predefined tasks
- Task sequence or structure (if required)
- Responsible roles or teams

✅ Benefits:

- Standardizes multi-step work
- Ensures no steps are missed
- Speeds up task creation
- Improves consistency across operations


### 3. Task Template

Used to standardize repeatable **Task List**. The list when made as a template, can be set to sequential (A Finish-to-Start (FS) relationship) or non-sequential (any task can Start and Finish).

Examples:

- Daily inspection
- Approval step
- Basic checklist item

✅ Focus: _Single, reusable task definition_


### 4. Assignment & Assignee<a id="task-assign"></a>

Tasks (or tasks within a Task List) can be assigned to specific **staff** as Assignee  
and assigned to **role** as Assignment

✅ Ensures the right person performs the work and right role is ready to pick up the task


### 5. Task Status<a id="task-status"></a>

Tracks progress of each task:

- Open (Start state with no Assignee)
- In Progress (has Assignee)
- Completed (Finish state)
- Cancelled (Finish state)

✅ Provides visibility to management and teams


### 6. Due Dates & Priority

Each task can have:

- **Due date** → When it must be completed

✅ Helps teams focus on important work


## How It Works (Simple Flow)

### Simple Task

1. Task is **created**
2. Assigned to a person or role
3. Work is performed
4. Task is marked **completed**

### Task List

1. A **Task List** is selected
2. The system generates a **list of tasks**
3. Tasks are assigned automatically or manually
4. Work is executed step by step
5. All tasks are tracked until completion


## Example (Simple Scenario)

### Example 1: Single Task

- Task: Inspect CNC Machine
- Assigned to: Technician


### Example 2: Task List

**Task List: Monthly Machine Maintenance**

Tasks generated:

1. Inspect machine condition
2. Lubricate components
3. Replace worn parts
4. Record maintenance results

✅ Technician completes each task  
✅ Entire package ensures full maintenance is done


## Automation & Integration

Tasks and Task Lists can be generated automatically from:

- **Workflow** → Approval processes
- **Asset** → Maintenance schedules
- **DMS** → Document reviews
- **LMS** → Training assignments

Task status is by default [**Open**](#task-status), whereby [**Assignment (role)**](#task-assign) is mandatory at creation.  
Task status is set to [**In Progress**](#task-status), when [**Assignee**](#task-assign) is populated.
Assignee is populated automatically when role contains only 1 staff/user and the user logins.

✅ Reduces manual work and ensures consistency


## Accountability & Tracking

The system ensures:

- Every task has a clear owner (assignee)
- Task Lists follow a standard process
- All actions are recorded
- Delays can be identified

✅ Improves discipline and operational control


## Integration with Other Modules

- **Org** → Determines where tasks belong
- **Person** → Identifies who performs the task
- **Job** → Defines responsible roles
- **Workflow** → Controls sequence and approvals
- **Notification** → Alerts users
- **Audit** → Tracks all actions


## Flexibility for Business

The system allows:

- Single tasks or grouped tasks (Task Lists)
- Reusable templates for efficiency
- Role-based or person-based assignments
- Cross-module task generation


## Summary

The Task module ensures that:

- Work is clearly defined and assigned
- Multi-step processes are standardized using Task Lists
- Tasks are tracked and completed on time
- Operations run smoothly and consistently


## In Simple Terms

Think of it like:

- **Task** → A single job
- **Task Template** → A reusable task
- **Task List** → A checklist of tasks
- **Assignment** → Who has the role to do it
- **Assignee** → Who does it

And the system ensures that **all work—simple or complex—is completed properly without missing any steps**.

# Calendar Management (Platform Overview)<a id="calendar"></a>

The **Calendar module** manages all company-related dates, schedules, and events in a single place.

It provides visibility of:

- Company events
- Public holidays
- Working days
- Staff shift schedules

## Why This Matters

In daily operations:

- Employees need to know working days and shifts
- Management needs to plan around holidays and events
- Operations depend on accurate scheduling
- Misalignment can cause delays or missed work

This module ensures the organization always knows:

✅ When people are working  
✅ When the company is closed  
✅ What events are happening  
✅ How shifts are structured

## Key Objectives

The Calendar module helps to:

- Centralize all company dates and schedules
- Align operations with working days and shifts
- Support workforce planning
- Improve coordination across teams

## Key Components

### 1. Event

Represents any important company-related activity.

Examples:

- Company townhall
- Safety day
- Maintenance shutdown
- Training session

✅ Each event includes:

- Name
- Date and time
- Type (company event, operational, etc.)
- Description

### 2. Holiday

Represents non-working days.

Examples:

- Public holidays
- Company-declared holidays

✅ Used to:

- Block scheduling
- Adjust work planning
- Inform employees

### 3. Workday

Defines whether a day is a working day or non-working day.

✅ Supports:

- Standard working calendars
- Custom work schedules

### 4. Shift Pattern

Defines how work is organized across time.

Examples:

- Day shift
- Night shift
- Rotational shift

✅ Includes:

- Start and end time
- Rest days
- Rotation rules

### 5. Shift Group

Groups employees under a common shift pattern.

Examples:

- Production Team A → Day Shift
- Production Team B → Night Shift

✅ Ensures:

- Consistent scheduling
- Easier management of large teams

## How It Works Together

The system combines all elements:

- **Holidays** define non-working days
- **Workdays** define normal operations
- **Shift Patterns** define working hours
- **Shift Groups** assign employees to schedules
- **Events** provide additional context

## Example (Simple Scenario)

- Holiday: Public Holiday (Monday)
- Shift Pattern: Day Shift (8AM–5PM)
- Team: Production Team A

Process:

- Monday is marked as a holiday → no work scheduled
- Tuesday resumes normal shift operations
- Team A follows day shift schedule

## Staff Scheduling

Each staff member’s work schedule is determined by:

- Their **shift group**
- The **shift pattern**
- The **calendar (holidays and workdays)**

✅ Ensures accurate and consistent scheduling

## Integration with Other Modules

- **Org** → Defines teams and departments
- **Person** → Links employees to shift groups
- **Job** → Aligns roles with shift requirements
- **Task** → Schedules tasks based on working time
- **LMS** → Plans training on available days
- **Asset** → Schedules maintenance during downtime

## Flexibility for Business

The system allows:

- Multiple shift patterns (e.g., 24/7 operations)
- Different calendars for different locations
- Special events and shutdown periods
- Dynamic reassignment of staff to shifts

## Custom Attributes

Calendar entries can include additional details such as:

- Location
- Department applicability
- Event category
- Priority level

✅ Easily adaptable to business needs

## Summary

The Calendar module ensures that:

- All company events and schedules are centralized
- Working and non-working days are clearly defined
- Staff shifts are properly managed
- Operations are aligned with time and availability

## In Simple Terms

Think of it like:

- **Holiday** → Days off
- **Event** → Things happening
- **Shift** → When people work
- **Group** → Who follows which shift

And the system ensures everyone knows **when to work and what is happening** across the company.

# Location Management (Overview)<a id="location"></a>

The **Location module** defines where things exist in the real world.

It provides a structured way to manage:

- Sites
- Buildings
- Areas
- Physical spaces


## Why This Matters

In operations, especially manufacturing:

- Work happens at specific locations
- Assets are placed in physical areas
- Staff may work across different sites
- Some processes depend on location

Without proper location management:

- Assets can be misplaced
- Work assignments become unclear
- Reporting becomes inaccurate

This module ensures:

✅ Clear visibility of all physical locations  
✅ Accurate tracking of assets and activities  
✅ Better planning and coordination


## Key Concept

Location answers the question:

> **“Where does this happen?”**


## Key Components

### 1. Location

Represents a physical place in the organization.

Examples:

- Factory Site
- Warehouse
- Office Building
- Production Line

✅ Each location includes:

- Name
- Type (site, building, area, etc.)
- Parent location (for hierarchy)


### 2. Location Hierarchy

Locations can be structured in levels.

Example:

- Site: Main Factory
    - Building: Block A
        - Area: Production Floor
            - Section: Line 1

✅ Enables:

- Clear physical structure
- Easy navigation and reporting


### 3. Location Assignment

Locations can be linked to different entities:

- **Organization** → Where the company operates
- **OrgUnit / Team** → Where work happens
- **Staff** → Where a person is based or assigned
- **JobPosition** → Where the job is performed
- **Asset** → Where equipment is located

✅ Creates strong real-world context


## How It Works (Simple Flow)

1. Locations are defined and structured
2. Entities (people, jobs, assets) are assigned to locations
3. Operations are executed based on location
4. Reports and tracking use location data


## Example (Simple Scenario)

- Site: Manufacturing Plant
- Area: Production Floor
- Asset: CNC Machine
- Staff: Technician

Process:

- CNC Machine is assigned to Production Floor
- Technician is assigned to same area
- Maintenance task is linked to that location

✅ Everyone knows where the work happens


## Location vs Organization (Important)

The system clearly separates:

- **Organization** → Structure (departments, teams)
- **Location** → Physical place

✅ Example:

- Maintenance Department (Org)
- Production Floor (Location)

Both are linked but **serve different purposes**


## Integration with Other Modules

- **Org** → Links departments to locations
- **Person** → Assigns staff to locations
- **Job** → Defines where jobs are performed
- **Asset** → Tracks physical placement of equipment
- **Task** → Executes work at specific locations
- **Workflow** → Supports location-based approvals
- **Calendar** → May vary by location (e.g., site holidays)


## Flexibility for Business

The system allows:

- Multiple sites (multi-plant operations)
- Flexible location hierarchy
- Shared or cross-location operations
- Location-based reporting and control


## Custom Attributes

Locations can include additional details such as:

- Address
- GPS coordinates
- Capacity
- Safety classification
- Operational status

✅ Easily extended for business needs


## Summary

The Location module ensures that:

- All physical places are clearly defined
- Assets, people, and work are tied to real locations
- Operations are better organized and traceable
- Multi-site businesses are fully supported


## In Simple Terms

Think of it like:

- **Location** → Where something exists
- **Hierarchy** → How places are organized
- **Assignment** → Who or what is at that place

And the system ensures that **everything in the organization is connected to a real-world location**.

# Workflow Management (Platform Overview)<a id="workflow"></a>

The **Workflow module** controls **how work moves through defined steps** in a structured and predictable way.

It defines:

- The sequence of steps
- Decision points
- Approvals and rejections
- When work can proceed or must stop

✅ Workflow provides **process control**, not execution  
✅ Actual work is performed using **Tasks**  
✅ Every workflow is **owned and managed by an OrgTeam**


## Why This Matters

In daily operations:

- Processes vary depending on who handles them
- Approvals may be skipped or done inconsistently
- Decisions are not clearly documented
- Ownership of processes is unclear

Without workflows:

- Requests are handled ad‑hoc
- Responsibility is unclear
- Errors and delays increase
- Auditability is weak

This module ensures the organization always knows:

✅ What step a process is in  
✅ What must happen next  
✅ Which **team owns the process**  
✅ Why a decision was made


## Key Concept

A **Workflow** answers the question:

> **“How should this be processed from start to finish, and which team is responsible?”**

✅ Workflow defines **control and rules**  
✅ Tasks define **actual work**  
✅ OrgTeams define **ownership and accountability**


## Workflow Ownership (Critical Rule)

Every workflow **must be owned by exactly one OrgTeam**.

✅ The owning OrgTeam:

- Defines the workflow structure
- Manages steps and transitions
- Owns approvals and decisions
- Is accountable for outcomes

✅ This ensures:

- Clear responsibility
- No orphaned processes
- Proper escalation paths


## Relationship with Requests (Very Important)

In most business processes:

👉 **A Request starts a Workflow**

- **Request** → Captures the need
- **Workflow** → Controls how the request is handled
- **OrgTeam** → Owns the workflow
- **Task** → Executes the work

✅ Without workflow → request is just data  
✅ With workflow → request becomes a controlled, owned process


## Relationship with Tasks (Critical Distinction)

- **Workflow** controls _when_ work is needed
- **Task** represents _what_ work is done
- **OrgTeam** determines _who owns the process_

✅ Tasks are created **inside workflow steps**  
✅ Transitions move the process forward  
✅ Not every step requires tasks


## Key Components

### 1. Workflow

Represents a complete business process **owned by an OrgTeam**.

Examples:

- Maintenance workflow (owned by Facilities Team)
- Purchase approval workflow (owned by Finance Team)
- Document approval workflow (owned by Compliance Team)
- Leave request workflow (owned by HR Team)

✅ Each workflow defines:

- Owning OrgTeam
- Start step
- End step(s)
- Allowed transitions
- Rules and conditions


### 2. Workflow Step

Represents a **state or checkpoint** in a process.

Examples:

- Submitted
- Under Review
- Approved
- In Progress
- Completed

✅ A step may:

- Require tasks
- Require approval
- Be automatic (no tasks)

✅ Steps operate under the authority of the owning OrgTeam


### 3. Transition

Defines how the process moves **from one step to another**.

Examples:

- Submit → Review
- Review → Approved
- Review → Rejected
- Approved → Completed

✅ Transitions:

- Enforce rules
- May require conditions
- Are controlled by the owning OrgTeam


### 4. Task Binding (Key Design)

Tasks are **attached to workflow steps**, not transitions.

✅ When a workflow **enters a step**:

- A Task or Task List may be generated
- Work is assigned to roles or staff
- Workflow waits

✅ When all required tasks are completed:

- Transition becomes available
- Workflow can move forward


### 5. Decision & Approval

Some workflow steps represent **decision points**.

Examples:

- Approve
- Reject
- Request changes

✅ Decisions:

- Are owned by the OrgTeam
- Control which transition is allowed
- Are recorded for audit
- May generate tasks or end the workflow


## How It Works (Simple Flow)

1. A **Request** is submitted
2. The linked **Workflow** starts
3. The owning **OrgTeam** takes control
4. Workflow enters a **Step**
5. Tasks may be generated
6. Tasks are completed
7. A **Transition** occurs
8. Workflow continues until completion

✅ Entire process is controlled, owned, and traceable


## Example (Request + Workflow + Task)

### Maintenance Request Workflow

**Owning OrgTeam: Facilities Team**

#### Step 1: Submitted

- Request created
- No tasks

➡ Transition: Auto → Review


#### Step 2: Review

- Task: Review maintenance request
- Assigned to: Facilities Supervisor

✅ Task completed → transition allowed


#### Step 3: Approved

- Decision recorded by Facilities Team
- Task List generated:
    - Inspect machine
    - Perform repair
    - Record results

➡ Workflow waits until all tasks complete


#### Step 4: Completed

- Workflow ends
- Request closed

✅ Facilities Team accountable for outcome


## What Workflow Does NOT Do (Important)

Workflow does NOT:

- Perform work
- Track task due dates
- Assign individual accountability

✅ Those belong to the **Task module**


## Integration with Other Modules

- [**Org Unit** / **Org Team**](#org) → Owns and governs workflows
- **Request** → Triggers workflows
- **Task** → Executes workflow steps
- **Version** → Controls versioned changes within steps
- **Notification** → Alerts on step changes
- **Audit** → Records every transition and decision


## Flexibility for Business

The system allows:

- Different workflows per OrgTeam
- Team‑specific approval rules
- Simple or complex step sequences
- Conditional approvals
- Parallel or sequential steps


## Workflow Initialization & Version Creation (Important)

For certain workflows (such as **Service Catalog** or other user-facing entities),  
the **first workflow step** may perform initialization actions.

✅ These may include:

- Creating an initial **Version** of the entity
- Setting the Version status (e.g. Draft or Approved)
- Marking the Version with: **is_menu = true**

✅ This typically happens when:

- The workflow enters its **first step**
- The entity is considered officially registered
- The owning OrgTeam accepts responsibility

✅ Rules:

- Menu visibility is still governed by **Version status**
- Only **Published** versions are shown to users
- The workflow only _triggers_ version creation — it does not manage menu logic

✅ This keeps responsibilities clean:

- **Workflow** → when initialization happens
- **Version** → what state and visibility mean
- **Service Catalog** → how menu is rendered

## Control & Traceability

The Workflow module ensures:

- Every process has a clear owner
- Every step is explicit
- Every transition is valid
- Decisions are accountable to a team

✅ Supports governance, audits, and operational control


## Summary

The Workflow module ensures that:

- Business processes are structured and repeatable
- Every workflow is owned by an OrgTeam
- Requests are handled consistently
- Work happens at the right time
- Decisions are controlled and auditable


## In Simple Terms

Think of it like:

- **Request** → Asking for something
- **Workflow** → The path it must follow
- **OrgTeam** → Who owns the path
- **Step** → A checkpoint
- **Task** → The work done at the checkpoint
- **Transition** → Moving to the next checkpoint

And the system ensures **every request follows a team‑owned, controlled process from start to finish**.

# Request Management (Business Overview)<a id="request"></a>

The **Request module** manages how users ask for something to be done within the organization.

It serves as the **starting point of most business processes**, and is typically **closely linked to workflows** to ensure proper handling, approvals, and execution.


## Why This Matters

In daily operations:

- Requests come from many sources (email, messages, verbal)
- Information may be incomplete or inconsistent
- No clear tracking of progress
- Approvals may be skipped or unclear

This module ensures:

✅ All requests are properly captured  
✅ Requests follow a controlled process  
✅ Progress is tracked from start to end  
✅ Responsibilities are clearly defined


## Key Concept

A **Request** answers the question:

> **“Something is needed — how do we get it done properly?”**


## Strong Link to Workflow (Important)

In most cases:

👉 A **Request is tightly linked to a Workflow**

- The **Request** captures the need
- The **Workflow** controls how it is processed

✅ Without workflow → request is just a record  
✅ With workflow → request becomes a controlled process


## Key Components

### 1. Request

Represents a demand, need, or submission from a user.

Examples:

- Request for maintenance
- IT support request
- Leave request
- Purchase request

✅ Each request includes:

- Request type
- Description
- Requester (person)
- Related data (asset, location, etc.)
- Status


### 2. Request Type

Defines the category of the request.

Examples:

- Maintenance Request
- IT Support Request
- HR Request

✅ Determines:

- Required information
- Associated workflow
- Responsible team


### 3. Workflow Binding

Each request type is usually linked to a **specific workflow**.

✅ Example:

- Maintenance Request → Maintenance Workflow
- Purchase Request → Approval Workflow

This ensures:

- Consistent handling
- Standard approval steps
- Controlled execution


### 4. Status

The status reflects the progress of the request:

- Submitted
- Under Review
- Approved / Rejected
- In Progress
- Completed
- Closed

✅ Status is typically driven by the workflow


### 5. Assignment

Requests are routed to:

- A **team**, or
- A **role**, or
- A specific **person**

✅ Based on workflow rules and responsibilities


## How It Works (Simple Flow)

1. User **submits a request**
2. System identifies the **Request Type**
3. Linked **Workflow is triggered**
4. Request goes through:
    - Review
    - Approval
    - Execution
5. Tasks may be generated
6. Request is **completed and closed**


## Example (Simple Scenario)

### Maintenance Request

1. Operator submits request for machine issue
2. Request triggers Maintenance Workflow
3. Supervisor reviews request
4. Approval is given
5. Task is assigned to technician
6. Repair is completed
7. Request is closed

✅ Entire process is tracked and controlled


## Relationship with Other Modules

- **Workflow** → Controls the lifecycle of the request (critical link)
- **Task** → Executes work generated from the request
- **Service Catalog** → Defines available request types (menu)
- **Org** → Determines ownership and routing
- **Person** → Identifies requester and assignee
- **Asset** → Links request to equipment
- **Notification** → Updates users on progress
- **Audit** → Tracks all actions


## Flexibility for Business

The system allows:

- Different request types per department
- Custom workflows per request type
- Simple or complex approval processes
- Integration across all business areas


## Control & Traceability

The module ensures:

- Every request is recorded
- Progress is visible at all times
- Actions and decisions are logged
- No request is lost or ignored

✅ Supports operational control and audits


## Summary

The Request module ensures that:

- All business needs are formally captured
- Requests are processed through proper workflows
- Work is executed in a controlled and consistent way
- The organization maintains visibility and accountability


## In Simple Terms

Think of it like:

- **Request** → Asking for something
- **Workflow** → The process to handle it
- **Task** → The work to complete it

👉 A request **starts the process**, and workflow ensures **it is done properly from start to finish**.

# System Panels (Overview)<a id="portal"></a>

The platform provides a unified user experience through **three complementary panels**, each serving a distinct purpose:

- **Staff Portal (TACKLE)** → Individual daily work
- **Team Panel** → Team coordination and delivery
- **Admin Portal** → System configuration and governance

Together, they form a complete operational ecosystem.


## Overall Concept

Each panel answers a different question:

- **Staff Portal** → “What do I need to do?”
- **Team Panel** → “How do we deliver as a team?”
- **Admin Portal** → “How is the system defined and controlled?”


## 1. Staff Service Portal – “TACKLE”

The **Staff Portal** is the **main working interface** for employees.

It acts as the **nerve center of daily activities**, where staff:

- Execute tasks
- Request services
- Access knowledge
- Manage personal work-related items


### TACKLE Structure

#### **T – Todo**

**Daily Catch & Priority Tasks**

- View assigned tasks
- Track pending and urgent work
- Monitor deadlines

✅ _Focus: What needs to be done now_


#### **A – Artifact**

**Personal Work Assets**

- Profile information
- Assigned assets (equipment, tools)
- Certificates and qualifications

✅ _Focus: What belongs to me_


#### **C – Catalog**

**Service Menu & Quick Access**

- Request services (IT, maintenance, HR)
- Browse available offerings
- Access external tools/links

✅ _Focus: What I can request_


#### **K – Knowledge**

**Documents & Learning**

- SOPs, policies, manuals
- Training materials
- Learning programs

✅ _Focus: What I need to know_


#### **L – Location**

**Physical Awareness**

- Floor plans
- Site navigation
- Location of assets and teams

✅ _Focus: Where things are_


#### **E – Emergency**

**Critical Support**

- Emergency contacts
- Incident reporting
- Risk and issue logging

✅ _Focus: What to do in urgent situations_


### Staff Portal Summary

👉 A **simple, action-oriented workspace** for employees to perform daily work efficiently.


## 2. Team Panel – Team Operations

The **Team Panel** provides a **shared workspace for teams** to manage work collectively.

It focuses on:

- Coordination
- Accountability
- Quality control
- Continuous improvement


### Why Team Panel Exists

Work is not done individually — it is delivered by **teams with structured roles**.

✅ Ensures:

- Clear responsibilities
- Better coordination
- Higher quality output


### Team Roles (Operating Model)

Each team operates using defined **functional roles**:


#### <img src="pics/team_scout.png" width="48" height="48"> Scout (Entry & Final Authority)

- Brings work into the team
- Owns intake (requests, assignments)
- Provides final sign-off

✅ Assures request fulfilment


#### <img src="pics/team_quartermaster.png" width="48" height="48"> Quartermaster (Resources)

- Manages tools, assets, inventory
- Ensures readiness before work starts

✅ Removes operational delays


#### <img src="pics/team_coach.png" width="48" height="48"> Coach (Capability Development)

- Trains team members
- Links LMS materials and assessments
- Improves skills over time

✅ Builds team competency


#### <img src="pics/team_players.png" width="48" height="48"> Player (Execution)

- Performs actual work
- Completes assigned tasks

✅ Core workforce


#### <img src="pics/team_captain.png" width="48" height="48"> Captain (Quality & Ownership)

- Oversees daily operations
- Ensures quality standards

✅ Team owner and controller


#### <img src="pics/team_tactician.png" width="48" height="48"> Tactician (Knowledge & Process)

- Writes procedures (SOPs)
- Maintains documentation
- Standardizes processes

✅ Keeps work consistent and structured


### Team Workflow (Simple)

1. **Scout** receives work
2. Work assigned to **Players**
3. **Quartermaster** ensures resources
4. Work is executed
5. **Captain** reviews quality
6. **Scout** signs off
7. **Tactician** updates knowledge
8. **Coach** improves skills


### Team Panel Summary

👉 A **team operating system** that ensures work is delivered **properly, consistently, and with quality**


## 3. Admin Portal – System Management

The **Admin Portal** is used to **configure and maintain the platform**.

It is not for daily operations, but for:

- Setup
- Control
- Governance


### Key Responsibilities

#### Configuration

- Organization structure
- Locations
- Job positions and roles
- Attributes


#### Process Setup

- Workflows
- Task templates & Work Packages
- Service Catalog


#### Access Control

- Roles and permissions
- ABAC (attribute-based access)
- User and staff management


#### Content Management

- Documents (DMS)
- Learning materials (LMS)


#### Monitoring

- Requests and services
- Tasks and workflows
- Reports and dashboards


### Admin Portal Summary

👉 A **control center** for configuring how the business operates within the system


## Panel Comparison

| Area    | Staff Portal (TACKLE) | Team Panel                  | Admin Portal          |
| ------- | --------------------- | --------------------------- | --------------------- |
| Focus   | Individual work       | Team coordination           | System configuration  |
| Users   | All employees         | Teams                       | Admins / managers     |
| Purpose | Execute tasks         | Deliver work as a team      | Configure & control   |
| Nature  | Simple & guided       | Operational & collaborative | Advanced & structured |


## Overall System View

The three panels work together:

- **Staff Portal** drives **execution (individual level)**
- **Team Panel** drives **coordination (team level)**
- **Admin Portal** drives **control (system level)**


### Final Summary

The platform ensures:

✅ Individuals know what to do  
✅ Teams know how to deliver  
✅ The system ensures everything is controlled


### In Simple Terms

- **Staff Portal** → _“Do my work”_
- **Team Panel** → _“Work together”_
- **Admin Portal** → _“Set the rules”_

👉 Together, they create a complete, scalable, and well-governed operational system.

# Service Catalog / Menu (Business Overview)<a id="catalog"></a>

The **Service Catalog module** defines the list of services that the organization provides internally.

It acts as a **menu of services** that employees can request, similar to choosing items from a menu.

✅ Services are **version‑controlled**  
✅ The catalog menu is driven by **published service versions**


## Why This Matters

In daily operations:

- Employees need support (IT, maintenance, facilities, etc.)
- Requests are often unclear or inconsistent
- No standard way to request services
- Tracking and prioritization becomes difficult

This module ensures:

✅ Services are clearly defined  
✅ Requests are standardized  
✅ Expectations are consistent  
✅ Service delivery is trackable


## Key Concept (Important)

A **Service** represents the **identity** of what the organization offers.

A **Version** represents:

- The **current definition** of the service
- The **request form**
- Whether the service appears in the **menu**

✅ The **menu is built from Version**, not directly from Service.


## Key Objectives

The Service Catalog helps to:

- Provide a clear list of available services
- Standardize how requests are made
- Control changes through versioning
- Define responsibilities and expectations
- Improve service efficiency and transparency


## Key Components

### 1. Service

A **Service** represents something the organization offers internally.

Examples:

- Repair Machine
- Request Laptop
- IT Support
- Facility Cleaning
- Equipment Calibration

✅ A Service defines:

- Name
- Description
- Owning team

✅ A Service **does not change frequently**  
✅ Changes happen through **Version**


### 2. Service Version (Menu Source)

Each Service has one or more **Versions**.

✅ A Version defines:

- The service request form
- Business rules
- SLA references
- Visibility in the menu

✅ Only versions that are:

- **Published**
- **Marked as menu-enabled**

appear in the Service Catalog.


### 3. Menu Visibility Flag (Critical Rule)

The **Version** model contains a boolean flag: **is_menu = true / false**

✅ Meaning:

- `true` → Version appears in the Service Catalog menu
- `false` → Version is hidden from the menu

✅ This allows:

- Drafting new service definitions safely
- Retiring old services without deleting them
- Controlling what users can request


### 4. Service Category (Menu Structure)

Services can be grouped into categories for easy navigation.

Examples:

- IT Services
- Maintenance Services
- HR Services
- Facility Services

✅ Categories organize **menu items (versions)**, not raw services


### 5. Request Form (Versioned)

Each service version defines its **request form**.

Examples:

- Machine ID for repair
- Issue description for IT support
- Location for facility requests

✅ Stored and controlled via **Version**  
✅ Changes require new versions


### 6. Service Level Agreement (SLA)

Defines expected service performance.

Examples:

- Response time: within 4 hours
- Resolution time: within 2 days

✅ SLA definitions are **versioned**
✅ Changes are traceable and auditable


### 7. Service Owner

Defines who is responsible for delivering the service.

- Usually an **OrgTeam**
- May reference roles (JobRole)

✅ Ownership is stable at Service level  
✅ Execution is handled through Workflow and Task


## How It Works (Simple Flow)

1. User opens the **Service Catalog**
2. System displays:
    - Published Versions
    - With `is_menu = true`
3. User selects a service
4. Versioned request form is shown
5. A **Request** is created
6. Linked **Workflow** is triggered
7. Tasks are generated and executed
8. Request is completed


## Example (Simple Scenario)

### Service: Repair Machine

- Service: Repair Machine
- Category: Maintenance
- Active Version: 1.2.0
- is_menu: true

Process:

1. Operator selects “Repair Machine”
2. Fills versioned request form
3. Request triggers Maintenance Workflow
4. Tasks assigned to technician
5. Repair completed
6. Request closed

✅ If a new version is prepared:

- It stays hidden until published
- Menu is unaffected


## Integration with Other Modules

- **Version** → Controls service definitions and menu visibility
- **Request** → Created from selected service version
- **Workflow** → Manages approval and execution flow
- **Task** → Executes the service work
- [**Org Unit** / **Org Team**](#org) → Owns the service
- **Asset** → Links service to equipment
- **Notification** → Updates users on progress
- **Audit** → Tracks all actions


## Flexibility for Business

The system allows:

- Multiple versions of the same service
- Safe changes without breaking the menu
- Temporary hiding of services
- Controlled rollout of new service definitions
- Clear audit trail of service changes


## Control & Traceability

The Service Catalog ensures that:

- Only approved service versions are visible
- Every service change is versioned
- Old definitions remain traceable
- Users always see the correct menu

✅ Supports governance and audits


## Summary

The Service Catalog ensures that:

- Services are clearly defined and version‑controlled
- The menu reflects only approved service definitions
- Requests are consistent and complete
- Work is executed through controlled workflows


## In Simple Terms

Think of it like:

- **Service** → What is offered
- **Version** → The current definition
- **is_menu** → Whether it appears on the menu
- **Request** → Ordering the service
- **Workflow** → How it is handled
- **Task** → The work to deliver it

And the system ensures **only approved, versioned services appear in the menu and are delivered properly**.

# Document Management System (Business Overview)<a id="document"></a>

The **Document Management System (DMS)** ensures that all organizational documents are properly controlled, accessible, and compliant with standards such as **ISO 9001**.

It manages documents using **Versioning**, **Workflow**, and a centralized **Audit Trail** to ensure full lifecycle control from creation to approval, publication, revision, and archival.


## Why This Matters (ISO 9001 Context)

ISO 9001 requires organizations to:

- Maintain **documented information**
- Ensure documents are **approved before use**
- Control **changes and revisions**
- Provide access to the **correct version**
- Prevent use of **obsolete documents**
- Maintain **records of approvals and changes**

This module ensures all these requirements are met consistently and auditable.


## Key Objectives

The DMS ensures:

✅ Only approved documents are used  
✅ Latest versions are always available  
✅ Changes are traceable and auditable  
✅ Documents are protected from unauthorized changes  
✅ Obsolete documents are controlled and archived


## Key Concepts

### 1. Document (Identity)

A **Document** represents the identity of controlled information.

Examples:

- SOPs (Standard Operating Procedures)
- Work Instructions
- Policies
- Technical Drawings
- Contracts

✅ A Document defines:

- Title
- Type
- Owning OrgTeam
- Applicability

✅ A Document **does not store content directly**  
✅ All content lives in **Versions**


### 2. Document Version (State)

Every document change creates a **new Version**, never overwriting existing ones.

✅ Each Version includes:

- Semantic version number (Major / Minor / Patch)
- Content (file or external reference)
- Status (Draft, Approved, Published, Archived)
- Change notes
- Creator and timestamp

✅ Only **Published** versions are valid for operational use  
✅ Older versions remain immutable and traceable


### 3. Approval Workflow (Control)

Document changes are governed by a **Workflow** owned by an **OrgTeam**.

✅ Workflow ensures:

- Draft → Review → Approval → Publication
- Proper segregation of duties
- Consistent handling of all documents

✅ The workflow:

- Controls state transitions
- Determines who can approve
- Triggers version state changes


### 4. Document Status (Derived from Version)

Document usability is determined by the **status of its Versions**:

- Draft → Work in progress
- Approved → Signed off, not yet active
- Published → Official and usable
- Archived → Obsolete, read-only

✅ Only **Published Versions** may be used in operations


### 5. Audit Trail (Traceability)

All document-related actions are recorded in the **central Audit Trail**.

Examples of audited actions:

- Document created
- Version created
- Version approved
- Version published
- Version archived

✅ Audit records include:

- Who performed the action
- What was done
- When it occurred
- Outcome and reason (if applicable)

✅ This satisfies ISO 9001 traceability requirements


## How It Works (Controlled Flow)

1. A **Document** is created (identity only)
2. A **Workflow** is initiated
3. Initial **Version** is created (Draft)
4. Workflow routes document for review
5. Version is **Approved**
6. Version is **Published**
7. Older published versions are archived
8. All actions are logged in **Audit Trail**

✅ No document bypasses workflow  
✅ No change happens without a version


## Example (Simple Scenario)

### Machine Operation SOP

- Document created by: Engineer
- Workflow owned by: Operations OrgTeam

Process:

1. Engineer creates Document
2. Draft Version 1.0.0 uploaded
3. Supervisor reviews via workflow
4. Manager approves
5. Version 1.0.0 published
6. Operators access published version only

Later update:

- Version 1.1.0 created
- Workflow repeats
- Version 1.0.0 archived after publication

✅ Full compliance and traceability


## ISO 9001 Compliance Mapping

### Document Control

- Unique document identity
- Version-controlled content
- Approval before publication

### Change Management

- No overwriting of content
- Clear version history
- Controlled workflow transitions

### Access & Distribution

- Only published versions visible
- Role-based access

### Obsolete Document Control

- Archived versions retained
- Prevent operational use

### Audit Evidence

- Centralized audit trail
- Full history of actions and approvals


## Integration with Other Modules

- **Workflow** → Controls document lifecycle
- **Version** → Stores document state and content
- [**Org Unit** / **Org Team**](#org) → Owns documents and workflows
- **Person / Job** → Defines authors, reviewers, approvers
- **Task** → Executes review or approval steps
- **Audit** → Records all document actions


## Flexibility for Business

The system allows:

- Different document types (SOP, policy, contract)
- OrgTeam-specific approval workflows
- Multi-site document control
- Custom review cycles
- Regulatory extensions


## Custom Attributes

Documents and versions may include:

- Document category
- Applicable department
- Regulatory reference
- Effective date
- Review interval

✅ Extendable without core changes


## Summary

The Document Management System ensures that:

- Documents are controlled through versioning
- Approvals are enforced via workflow
- Only valid versions are used
- All actions are auditable
- ISO 9001 requirements are consistently met


## In Simple Terms

Think of it like:

- **Document** → The identity
- **Version** → The controlled content
- **Workflow** → The approval path
- **Audit Trail** → The evidence

And the system ensures everyone always uses the **right document, approved the right way, at the right time**.

# Learning (Business Overview)<a id="learn"></a>

The **Learning Management System (LMS)** helps the organization manage employee training, skills, and certifications in a **controlled, auditable, and compliant** manner.

It ensures that:

- Employees are properly trained
- Learning materials are controlled and approved
- Required certifications are tracked
- Skills are aligned with job requirements

✅ Learning content is **version‑controlled**  
✅ Training lifecycle is governed by **workflow**  
✅ All actions are recorded in the **audit trail**


## Why This Matters

In a manufacturing environment:

- Employees must follow strict procedures
- Certifications may be mandatory (e.g., safety, machinery)
- Skills must match job roles
- Training records are required for audits and compliance

Without proper control:

- Outdated materials may be used
- Training approvals may be skipped
- Certification evidence may be incomplete
- Audit readiness is weakened

This module ensures the company always knows:

✅ Who is trained  
✅ On which approved material  
✅ With what certification status  
✅ And when retraining is required


## Key Objectives

The Learning module helps to:

- Control learning materials through versioning
- Enforce approval of training content
- Track certifications and expiry
- Align skills with job requirements
- Provide audit‑ready training records


## Key Concepts

### 1. Training (Identity)

A **Training** represents a learning program or subject offered by the organization.

Examples:

- Safety Training
- Machine Operation Training
- Quality Procedures

✅ Training defines:

- Title
- Purpose
- Owning OrgTeam
- Applicability (roles, departments)

✅ Training **does not store content directly**  
✅ Content is managed via **Versions**


### 2. Training Version (State)

Each update to training material creates a **new Version**.

✅ A Training Version includes:

- Version number (Major / Minor / Patch)
- Learning material (documents, videos, links)
- Status (Draft, Approved, Published, Archived)
- Change notes
- Creator and timestamp

✅ Only **Published Versions** may be used for training  
✅ Older versions remain immutable and traceable


### 3. Approval Workflow (Control)

Training materials are governed by a **Workflow** owned by an **OrgTeam**.

✅ Workflow ensures:

- Draft → Review → Approval → Publication
- Proper authorization
- Controlled rollout of training changes

✅ Workflow:

- Controls when versions are approved
- Determines who can publish training
- Prevents use of unapproved materials


### 4. Certification<a id="certificate"></a>

A **Certification** represents proof that a person has completed training using an **approved version**.

Examples:

- Forklift License
- Safety Certification
- ISO Compliance Training

✅ Certifications:

- Are issued only from **Published Training Versions**
- May have validity or expiry dates
- Are linked to audit records


### 5. Competency

Defines the **skill level** of a person.

Examples:

- Beginner
- Intermediate
- Expert

✅ Competency is:

- Influenced by training and certification
- Used to determine job fitness
- Auditable and time‑bound if required


## How It Works Together

The LMS connects learning, people, and jobs:

1. Training is defined (identity)
2. Training material is added as a **Draft Version**
3. Workflow routes version for approval
4. Version is **Published**
5. Employees attend training
6. Certification is issued
7. Competency level is updated
8. All actions are logged in **Audit Trail**

✅ No training bypasses approval  
✅ No certification exists without approved material


## Example (Simple Scenario)

- Role: Machine Operator
- Required Training: Machine Safety Training
- Person: Ahmad

Process:

1. Safety Training is published as Version 2.0.0
2. Ahmad is assigned training
3. Ahmad completes training
4. Certification is issued
5. Ahmad’s competency is updated
6. Audit trail records:
    - Training completion
    - Certification issuance

✅ Ahmad is now qualified and auditable


## Compliance & Audit

The LMS ensures:

- Only approved training materials are used
- Certification evidence is traceable
- Expired certifications are flagged
- Training history is preserved

✅ Critical for:

- Safety compliance
- ISO audits
- Regulatory inspections


## Integration with Other Modules

- **Version** → Controls training material changes
- **Workflow** → Governs approval and publication
- **Audit** → Records training, certification, and changes
- [**Org Unit** / **Org Team**](#org) → Owns training programs
- **Person** → Identifies who is trained
- **Job** → Defines required training
- **Access / Authorization** → Restricts actions based on certification


## Flexibility for Business

The system allows the organization to:

- Define different training per role or department
- Update learning materials safely through versioning
- Support internal and external training
- Handle multiple certifications per employee
- Enforce retraining cycles


## Custom Attributes

Training, versions, and certifications may include:

- Training provider
- Assessment score
- Validity period
- Skill category
- Regulatory reference

✅ Easily extended without core changes


## Summary

The Learning module ensures that:

- Training materials are controlled and approved
- Employees are trained on the correct versions
- Certifications are valid and traceable
- Skills align with job requirements
- The organization remains audit‑ready


## In Simple Terms

Think of it like:

- **Training** → What people need to learn
- **Version** → The approved learning material
- **Workflow** → How training gets approved
- **Certification** → Proof they passed
- **Competency** → How good they are at it
- **Audit Trail** → The evidence

And the system ensures the **right people learn the right material, approved the right way, at the right time**.

# Asset Management (Business Overview)<a id="asset"></a>

The **Asset Management module** manages all physical and operational assets within the organization across their full lifecycle.

It ensures that assets are:
- Properly registered
- Maintained regularly
- Used efficiently
- Tracked until disposal

---

## Why This Matters

In a manufacturing environment:

- Machines and equipment are critical to operations
- Breakdowns cause production loss
- Poor tracking leads to inefficiencies
- Compliance requires maintenance records

This module ensures the organization always knows:

✅ What assets exist  
✅ Where they are  
✅ What condition they are in  
✅ When maintenance is due  
✅ Who is responsible  

---

## Key Objectives

The Asset module ensures:

- Full visibility of all assets
- Planned and controlled maintenance
- Reduced downtime
- Traceability of asset history
- Support for audits and compliance

---

## Key Components

### 1. Asset Register

A central list of all assets.

Examples:
- Machines/Equipment
- Tools
- Movers : Vehicles/Transport/Trolley
- Space : Storage/Compartment/Rack

✅ Each asset includes:
- Asset ID
- Name and type
- Brand(Maker) and Model
- Location
- Owner / responsible team
- Status (active, under maintenance, disposed)

---

### 2. Asset Lifecycle

Tracks the full life of an asset:

1. Acquisition (purchase or installation)  
2. Operation (in use)  
3. Maintenance (servicing and repair)  
4. Disposal (retirement or replacement)  

✅ Ensures proper planning and cost control

---

### 3. Maintenance Management

Ensures assets remain in good condition.

Types of maintenance:
- **Preventive** → Scheduled servicing  
- **Corrective** → Fix after breakdown  
- **Predictive** → Based on condition or usage  

✅ Helps reduce unexpected failures

---

### 4. Work Orders

Used to manage maintenance activities.

✅ Includes:
- Task details
- Assigned personnel
- Required parts/tools
- Status tracking

---

### 5. Asset Status

Shows current condition of the asset:

- Setup / Installing
- In Use / Active
- In Maintenance
- Out of Service
- Obsolete / Disposed

✅ Helps operations make quick decisions

---

## How It Works (Simple Flow)

1. Asset is **registered** in the system, status becomes "Setup"  
2. Assigned to a **location/team/staff**, status becomes "In Use"  
3. Maintenance schedules are defined  
4. Work orders are created when needed  
5. Maintenance activities are recorded  
6. Asset history is tracked  
7. Asset is eventually **retired or replaced**

---

## Example (Simple Scenario)

- Asset: CNC Machine  
- Location: Production Floor  
- Responsible: Maintenance Team  

Process:
- Machine is registered
- Monthly maintenance is scheduled
- A work order is created automatically
- Technician performs maintenance
- Record is saved in asset history
- Machine continues operation

---

## Compliance & Audit Support

The module supports operational and quality standards (including ISO practices) by:

- Maintaining complete asset history
- Recording all maintenance activities
- Ensuring scheduled maintenance is not missed
- Providing traceability of actions and responsibilities

✅ Useful for audits and inspections  

---

## Integration with Other Modules

- **Org** → Defines asset location and ownership  
- **Person** → Identifies responsible personnel  
- **Job** → Assigns roles (e.g., technician, approver)  
- **Workflow** → Controls approvals for request, maintenance or disposal  
- **Task** → Generates tasks to perform action on asset  
- **Audit** → Tracks all asset-related changes 
- **Entitlement** → Validation for request, ownership within limits     

---

## Flexibility for Business

The system allows the organization to:

- Manage multiple asset types
- Support multi-site operations
- Customize maintenance schedules
- Track both simple and complex equipment

---

## Extra / Custom Attributes

Assets can include additional business-specific details:

- Manufacturer
- Serial number
- Warranty period
- Maintenance frequency
- Operating capacity

✅ Easily extendable without redesign

---

## Summary

The Asset Management module ensures that:

- All assets are properly tracked
- Maintenance is planned and executed
- Downtime is minimized
- Asset history is available for audits
- The organization operates efficiently

---

## In Simple Terms

Think of it like:

- **Asset** → The equipment or item  
- **Maintenance** → Keeping it in good condition 
- **Request** → Workflow to acquire and place it to In Use.  
- **Work Order** → The job to fix or service it  
- **Lifecycle** → From purchase to disposal   

And the system ensures every asset is **well-managed from start to end**.
