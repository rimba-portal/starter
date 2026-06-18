# Rimba Tree: Ver (Version Management)

```text
config/
└── ver.php

database/
├── migrations/
│   ├── create_versions_table.php
│   └── create_version_dependencies_table.php (optional)
│
└── seeders/
    └── VersionSeeder.php

util/
└── SemanticVersion.php


app/Trees/Ver/
│
├── Actions/
│   ├── CreateVersion.php
│   ├── ReleaseVersion.php
│   ├── ArchiveVersion.php
│   ├── ChangeVersionStatus.php
│   ├── GenerateNextVersion.php
│   ├── VerifyVersionChecksum.php
│   ├── DeleteVersion.php
│   └── SyncVersionDependencies.php
│
├── Builders/
│   └── VersionBuilder.php
│
├── Events/
│   ├── VersionCreated.php
│   ├── VersionReleased.php
│   ├── VersionArchived.php
│   ├── VersionDeleted.php
│   ├── VersionStatusChanged.php
│   ├── VersionBecameEffective.php
│   └── VersionExpired.php
│
├── Http/
│   │
│   ├── API/
│   │   └── Resources/
│   │       └── VersionResource.php
│   │
│   └── UI/
│       │
│       ├── Admin/
│       │   │
│       │   ├── Resources/
│       │   │   ├── VersionResource.php
│       │   │   │
│       │   │   └── VersionResource/
│       │   │       │
│       │   │       ├── Pages/
│       │   │       │   ├── ListVersions.php
│       │   │       │   ├── CreateVersion.php
│       │   │       │   ├── EditVersion.php
│       │   │       │   └── ViewVersion.php
│       │   │       │
│       │   │       └── RelationManagers/
│       │   │           └── VersionsRelationManager.php
│       │   │
│       │   ├── Pages/
│       │   │   ├── VersionDashboard.php
│       │   │   ├── CurrentVersions.php
│       │   │   └── ObsoleteVersions.php
│       │   │
│       │   └── Widgets/
│       │       ├── LatestVersionsWidget.php
│       │       ├── ReleasedVersionsWidget.php
│       │       ├── DraftVersionsWidget.php
│       │       ├── ExpiringVersionsWidget.php
│       │       └── ObsoleteVersionsWidget.php
│       │
│       └── Staff/
│           │
│           ├── Resources/
│           │   └── VersionResource.php
│           │
│           ├── Pages/
│           │   ├── CurrentVersions.php
│           │   └── VersionViewer.php
│           │
│           └── Widgets/
│               └── CurrentVersionsWidget.php
│
├── Jobs/
│   ├── VerifyVersionChecksums.php
│   ├── ArchiveExpiredVersions.php
│   ├── UpdateCurrentVersions.php
│   └── NotifyVersionOwners.php
│
├── Listeners/
│   ├── NotifyVersionReleased.php
│   ├── NotifyVersionArchived.php
│   ├── UpdateCurrentVersionPointer.php
│   └── UpdateDependentVersions.php
│
├── Models/
│   ├── Version.php
│   └── VersionDependency.php
│
├── Observers/
│   └── VersionObserver.php
│
├── Policies/
│   └── VersionPolicy.php
│
├── Services/
│   ├── SemanticVersionService.php
│   ├── VersionResolverService.php
│   ├── VersionComparisonService.php
│   ├── VersionChecksumService.php
│   ├── VersionDependencyService.php
│   └── VersionContentService.php
│
├── Traits/
│   └── HasVersions.php
│
└── Enums/
    ├── VersionStatus.php
    ├── VersionIncrementType.php
    └── ContentType.php


```

```text
DATABASE

versions
├── id
├── versionable_type
├── versionable_id
├── version
├── major
├── minor
├── patch
├── status
├── content_type
├── content_url
├── checksum
├── effective_from
├── effective_until
├── released_at
├── notes
├── created_by
├── updated_by
├── created_at
└── updated_at


OPTIONAL

version_dependencies
├── id
├── version_id
├── depends_on_version_id
├── created_at
└── updated_at


```

```text
MODEL RELATIONSHIPS

Version
├── morphTo(versionable)
├── belongsTo(createdBy)
├── belongsTo(updatedBy)
├── belongsToMany(dependencies)
└── belongsToMany(dependents)

VersionDependency
├── belongsTo(version)
└── belongsTo(dependsOnVersion)


```

```text
TRAIT

HasVersions
├── versions()
├── currentVersion()
├── releasedVersions()
├── draftVersions()
├── latestVersion()
├── createVersion()
├── releaseVersion()
└── archiveVersion()

```

```text

SUPPORTED VERSIONABLE MODELS

Dms
├── Document
├── Policy
├── SOP
├── WorkInstruction
└── Manual

Pwm
├── Workflow
├── WorkflowTemplate
└── TaskTemplate

Tos
├── ServiceCatalog
├── ServiceOffering
└── ServicePackage

Lcm
├── ContractTemplate
└── ClauseLibrary

Lms
├── Course
├── Module
├── Quiz
└── CertificateTemplate

Eam
├── AssetSpecification
├── MaintenanceProcedure
└── CalibrationProcedure

General
├── JsonTemplate
├── ApiSpecification
├── FormTemplate
└── ReportTemplate


```

```text
STATUS FLOW

Draft
  ↓
Review
  ↓
Approved
  ↓
Released
  ↓
Obsolete
  ↓
Archived


```

```text
SEMANTIC VERSION FLOW

Create Version
├── 1.0.0
│
├── Patch Release
│   └── 1.0.1
│
├── Minor Release
│   └── 1.1.0
│
└── Major Release
    └── 2.0.0


```

```text
COMMON ACTIONS

CreateVersion
ReleaseVersion
ArchiveVersion
ChangeVersionStatus
GenerateNextVersion
VerifyVersionChecksum
DeleteVersion
SyncVersionDependencies


```

```text
COMMON SERVICES

SemanticVersionService
VersionResolverService
VersionComparisonService
VersionChecksumService
VersionDependencyService
VersionContentService


```

```text
COMMON BUILDER METHODS

released()
draft()
review()
approved()
archived()
obsolete()
effective()
current()
latest()
major($major)
minor($major, $minor)
patch($major, $minor, $patch)


```

```text
FILAMENT RESOURCE FEATURES

VersionResource
├── View Version
├── Create Version
├── Edit Version
├── Release Version
├── Archive Version
├── Compare Versions
├── Preview Content URL
├── Verify Checksum
└── View Dependencies


```

```text
DESIGN PRINCIPLE

Ver owns:
├── semantic version number
├── lifecycle status
├── effective dates
├── release dates
├── checksum validation
├── dependency graph
└── content location

Ver does NOT own:
├── actual file contents
├── markdown contents
├── PDFs
├── JSON bodies
├── SOP text
└── workflow definitions

Actual content lives in:
|── S3
├── GitHub
├── SharePoint
├── External URLs
└── Any content repository

Vms only stores:

content_url
```
