# Tree 
Apart from the Starter (base application, Laravel with Filament and Spatie Permission)

> **Flow** - The Workflow module and uses workpackage
>
> **Work** - The Workpackage module is the sequential set of Checklists. Checklist is the non sequential set of Tasks
>
> **Link** - Various shared modules, mainly used to bind between resources. Entitlement, Agreement, ...
>
> **Menu** - Catalog listing of URL links (using versioning module in bit-es)
>
> **Time** - Modules pertaining to schedule, event, shift and calendar

# Bark
Enhancement and modification add on to the application (Tree)
> **Trail** - The module on keeping an audit trail on any model record changes.
>
> **Branding** - The module on customizing the look and feel of Filament UX.
>
> **Who** - The authentication module which customs the standard Filament Auth 
>
> **Can** - The authorization module which merges the ABAC and RBAC together with use of Spatie Permission as much as possible.

# Twig
Additional modules that will use application (Tree)
ie. Document Management, Learning Management, etc.
> **Dms** - Document Management System, extension to the bit-es/versioning on records which are document related.
> 
> **Lcm** - Contract Management System, extension to the bit-es/agreement.
>
> **Hrm** - HR Management Module, extension to the organization staff. Records of staff with employment under this company.
> 
> **Lms** - Learning Management System.
> 
> **Eam** - Enterprise Asset Management Module.
> 
> **Tos** - Team Offering Service, managing of service offering (service and asset) by team.
> 


# Leaf
Seeding of template data usually in form of json format. Seed into application database.
