# Enterprise Manufacturing Platform

Resource Integration for Manufacturing & Business Alignment
A modular, package-based platform for manufacturing organizations, designed to support core business lifecycles such as employees, assets, services, and documents.  
Built with **laravel/framework v13**, **filament/filament v5** and **spatie/laravel-permission v7**, emphasizing clean separation of concerns, reusability, and scalability.

---

## Architecture Overview

The platform is organized into four layers:

- **BUSINESS** – Business domain functionality
- **FOUNDATION** – Core organizational and process models
- **PLATFORM** – Technical and UI capabilities
- **SUPPORT** – Cross-cutting operational services

---

## Packages

### BUSINESS
Implements organization's business domains, to be more precise the business requirement specs.

- **DMS (Document Management System)**  
  Manages SOPs, policies, drawings, contracts, and controlled documents.



- **LMS (Learning Management System)**  
  Manages training, certifications, and employee competency records.

- **EAM (Enterprise Asset Management)**  
  Full asset lifecycle management including registration, utilization, maintenance, and disposal.

---

### FOUNDATION
Defines the core structure of the organization. Applying the organization context to the platform.

- **Branding**  
  Manages corporate identity such as themes, logos, and layout configuration.

- **TOS (Team Offering System)**  
  Handles internal service requests such as maintenance, IT, and facilities, including SLA tracking.

- **WFP (Workforce Planning System)**  
  Organization Structures are OrgCorp, OrgUnit, OrgTeam. These models carry organizational attributes, not people.
  Models are JobPosition, JobRole, JobContract. These models carry job / work attributes, time‑bound and contract‑aware.
  Person are User, Staff, Employee. These models carry person attributes, not work definition.

---

### PLATFORM
Provides base technical capabilities used by all packages.

- **UX (Filament)**  
  Define the Filament setup and panels that exist; mainly Staff Panel and Admin Panel

- **AuthN**  
  Handles authentication, SSO, and identity federation (e.g. LDAP, AD, Keycloak).

- **AuthZ**  
  `AuthN` in Platform handles `Authentication`, therefore `AuthZ` handles the next step, `Authorization`. Roles, permissions, and segregation of duties enforcement. Roles are create thru ABAC from Attributes or can be manually added.

- **Sync**  
  Manages data synchronization with external systems.

- **Task**  
  Generic task and task_template for to do actions for any role and/or specific assignee.

- **Workflow**  
  Generic workflow and lifecycle engine for approvals, state transitions, and escalations.

---

### SUPPORT
Provides observability, compliance, and communication services.

- **Notification**  
  Sends system notifications, approvals, reminders, and alerts via multiple channels.

- **Audit**  
  Maintains audit trails and change history for compliance and traceability.

- **Reporting**  
  Provides operational reports, KPIs, and management dashboards.

---
## Vendor Packages Structure 


| Folder/Directory | Mainly contains |
|------|------|
| vendor\bit-es\<Layer>\config | stores required configs for this layer
| vendor\bit-es\<Layer>\database\migrations | tables for models in all packages of this layer |
| vendor\bit-es\<Layer>\src\<Package> |  |
| vendor\bit-es\<Layer>\src\<Package>\Concerns | *Traits classes* |
| vendor\bit-es\<Layer>\src\<Package>\Entities | *Models classes* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Admin\Resources | *Filament Resource for Admin Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Admin\Pages | *Filament Pages for Admin Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Admin\Widgets | *Filament Widgets for Admin Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Staff\Resources | *Filament Resource for Staff Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Staff\Pages | *Filament Pages for Staff Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\UI\Staff\Widgets | *Filament Widgets for Staff Panel* |
| vendor\bit-es\<Layer>\src\<Package>\Http\API\Resources | *Models classes* |
| vendor\bit-es\<Layer>\src\<Package>\Http\Requests |  |
| vendor\bit-es\<Layer>\src\<Package>\Actions | *Single Action classes* |
| vendor\bit-es\<Layer>\src\<Package>\Policies | *Policies for models* |
| vendor\bit-es\<Layer>\src\<Package>\Observers | *Observers for models* |
| vendor\bit-es\<Layer>\src\<Package>\Jobs |  |
| vendor\bit-es\<Layer>\src\<Package>\Events |  |
| vendor\bit-es\<Layer>\src\<Package>\Listeners |  |
| vendor\bit-es\<Layer>\src\<Package>\Services | *Services classes (multiple actions)* |
|||

## Design Principles

- Business-first domain modeling
- Laravel Naming Conventions or simply Eloquent Conventions for database-related items
- Namings are to be of simple words (audience take English as second language)
- Clear package boundaries
- Workflow-driven lifecycles
- Reusable and configurable components
- Multi-plant and multi-company ready

---

## Intended Use

This platform serves as a foundation for:
- Manufacturing ERP / MES extensions
- Internal enterprise systems
- Modular SaaS or open-core products

---

## License

To be defined per package (open-core friendly).