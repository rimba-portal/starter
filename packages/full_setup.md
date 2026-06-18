# Rimba Foundation Starter Kit

The Rimba Foundation Starter Kit provides the minimum set of capabilities required for an organization to operate on the platform.

These capabilities are intentionally designed to be useful on their own while also serving as the foundation upon which optional Business Modules, Platform Modules, and Content Packs can be installed.

The Foundation Starter Kit focuses on:

- People and organizational structure
- Access control
- Work execution
- Requests and approvals
- Digital assets
- Physical assets
- Scheduling and time
- Incident reporting

Advanced governance, compliance, analytics, optimization, and industry-specific capabilities are provided through paid modules.

---

# Identity

## Purpose

Identity manages who can access the system.

It provides the link between an authenticated user account and the staff member performing work within the organization.

## Core Records

- User
- Staff
- Login Provider

## Examples

- Ahmad logs in using an email and password.
- Siti logs in using Microsoft Entra ID.
- John logs in using LDAP.

## Provides

- Authentication identity
- User account management
- Staff-to-user linking

## Extended By

- LDAP Integration Module
- Face Recognition Login Module
- Identity Provider (IdP) Module

---

# Authorization

## Purpose

Authorization manages what users are allowed to see and do.

It combines Role-Based Access Control (RBAC) and Attribute-Based Access Control (ABAC).

## Core Records

- Role
- Permission
- Attribute
- Policy

## Examples

- Maintenance Manager may approve maintenance requests.
- Operators may only view production tasks.
- Employees from Site A may not access Site B records.

## Provides

- Roles
- Permissions
- Access policies
- Attribute-based restrictions

## Extended By

- Advanced Governance Modules
- Compliance Modules

---

# Organization

## Purpose

Organization represents all entities and organizational structures involved in business operations.

## Core Records

### OrgCorp

Represents any organization.

Examples:

- Company
- Vendor
- Customer
- Government Agency
- University
- Contractor
- Sister Company
- Business Partner

### OrgUnit

Represents hierarchical organizational structures.

Examples:

- Factory
- Department
- Division
- Branch

### OrgTeam

Represents operational responsibility groups.

Examples:

- Maintenance Team
- QA Team
- HR Team

### JobPosition

Represents a responsibility within the organization.

Examples:

- Production Manager
- Technician
- QA Engineer

### Staff

Represents a person performing work for the organization.

## Provides

- Organizational hierarchy
- Team ownership
- Position assignments
- Staff management

## Extended By

- People Management Module
- Workforce Planning Module

---

# Versioning

## Purpose

Versioning stores references to version-controlled content.

It does not store files directly.

Instead, it stores metadata and URLs pointing to the actual content.

## Core Records

- Version
- VersionContent

## Examples

- SOP Version 1
- SOP Version 2
- Laptop Request Form Version 3

## Supported Storage Locations

- Amazon S3
- MinIO
- Azure Blob Storage
- GitHub
- Internal Document Server

## Provides

- Version numbering
- Content references
- Change history

## Used By

- Document
- Service Catalog
- Future Business Modules

---

# Base Contract

## Purpose

Base Contract stores the minimum information required to represent an agreement between parties.

It is intentionally lightweight.

Full contract lifecycle management is provided by the Contract Management Module.

## Core Records

- BaseContract
- ContractParty

## Contract Parties

May include:

- Company
- Vendor
- Customer
- Contractor
- Government Agency

## Contract Subjects

Contracts may be linked to:

- Asset
- Service
- Document
- Other business records

## Provides

- Contract reference
- Start date
- End date
- Involved parties
- Linked business records

## Extended By

- Contract Management Module

---

# Document

## Purpose

Document represents digital business assets.

Documents store metadata while Versioning manages the actual content references.

## Core Records

- Document
- DocumentType

## Examples

- SOP
- Policy
- Work Instruction
- Contract
- Drawing
- Photo

## Provides

- Document registry
- Categorization
- Ownership
- Version references

## Extended By

- Document Management Module
- ISO 9001 Content Packs

---

# Workflow

## Purpose

Workflow manages business processes and approvals.

It defines how work moves through an organization.

## Core Records

- Workflow
- RequestFulfilment
- TaskTemplate
- Transition

## Examples

- Purchase Approval
- Leave Approval
- Asset Request Approval

## Provides

- Process orchestration
- Approval routing
- State transitions

## Used By

- Service Catalog
- Asset Management
- Risk Management
- Quality Management

---

# Task

## Purpose

Task represents a unit of work assigned to a person or team.

Tasks are typically generated from workflows.

## Core Records

- Task
- ChecklistItem
- Assignment
- Comment

## Statuses

- Queued
- Active
- Done

## Examples

- Review Purchase Request
- Inspect Equipment
- Complete Training

## Provides

- Work assignment
- Progress tracking
- Task ownership

---

# Service Catalog

## Purpose

Service Catalog defines services and requestable offerings available to users.

It replaces the earlier concept known as TOS (Team Offering Service).

## Core Records

- Service
- ServiceRequest
- ServiceForm

## Examples

- Request Laptop
- Request Access Card
- Request Training
- Request Company Vehicle

## Provides

- Service definitions
- Request forms
- Fulfilment workflows

## Uses

- Workflow
- Entitlement
- Versioning

---

# Entitlement

## Purpose

Entitlement determines who is allowed to request which services or assets.

## Core Records

- Entitlement
- EligibilityRule
- ApprovalRule

## Examples

- Managers may request company vehicles.
- Engineers may request CAD software.
- Operators may request safety equipment.

## Provides

- Eligibility checks
- Request authorization
- Approval requirements

## Uses

- Roles
- Permissions
- Attributes
- Positions
- Teams

---

# Asset

## Purpose

Asset provides a registry of company-owned assets.

This foundation capability focuses on asset registration only.

Advanced maintenance and lifecycle management are provided separately.

## Core Records

- Asset
- AssetCategory
- AssetModel

## Asset Model

Contains:

- Make
- Model
- Manufacturer

## Relationships

Assets may be linked to:

- OrgTeam
- OrgUnit
- BaseContract

## Examples

- Forklift
- Laptop
- CNC Machine
- Company Vehicle

## Provides

- Asset registry
- Ownership tracking
- Contract linking

## Extended By

- Asset Management Module
- Maintenance Management Module

---

# Calendar

## Purpose

Calendar manages organizational time and scheduling information.

## Core Records

- Calendar
- Holiday
- Shift
- Roster
- Event

## Examples

- Public Holidays
- Factory Shutdown
- Day Shift
- Night Shift

## Provides

- Working calendars
- Shift schedules
- Organizational events

## Used By

- Workforce Planning
- Task Scheduling
- Manufacturing Modules

---

# Risk

## Purpose

Risk provides incident reporting and resolution tracking.

It is intentionally focused on operational incidents rather than formal risk management methodologies.

## Core Records

- Incident
- IncidentType
- Resolution

## Examples

- Forklift Breakdown
- Network Outage
- Service Failure
- Document Error

## Relationships

Incidents may be linked to:

- Asset
- Service
- Workflow
- Document

Incidents may be assigned to:

- OrgTeam
- Staff

## Provides

- Incident reporting
- Incident assignment
- Resolution tracking
- Closure tracking

## Extended By

- Risk Management Module
- CAPA Module
- Quality Management Module

---

# Foundation Summary

The Foundation Starter Kit includes:

- Identity
- Authorization
- Organization
- Versioning
- Base Contract
- Document
- Workflow
- Task
- Service Catalog
- Entitlement
- Asset
- Calendar
- Risk

These capabilities provide a complete operational foundation for most organizations while allowing advanced business functionality to be added through optional modules.