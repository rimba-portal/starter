# Rimba Tree: Ver (Version Management)

```text
config/
└── ver.php

database/
├── migrations/
│   ├── create_versions_table.php
│   └── create_version_links_table.php (optional, replaces dependency graph)
│
└── seeders/
    └── VersionStatusSeeder.php

util/
└── SemanticVersion.php


app/Trees/Ver/

├── Actions/
│   ├── CreateVersion.php
│   ├── IncrementVersion.php
│   ├── PublishVersion.php
│   ├── ApproveVersion.php
│   ├── ArchiveVersion.php
│   ├── ChangeVersionStatus.php
│   ├── GenerateNextVersion.php
│   ├── ValidateVersionIntegrity.php
│   └── ResolveContentReference.php
│
├── Builders/
│   └── VersionQueryBuilder.php
│
├── Events/
│   ├── VersionCreated.php
│   ├── VersionApproved.php
│   ├── VersionPublished.php
│   ├── VersionArchived.php
│   ├── VersionSuperseded.php
│   └── VersionEffective.php
│
├── Http/
│   ├── API/
│   │   └── Resources/
│   │       └── VersionResource.php
│   │
│   └── UI/
│       ├── Admin/
│       │   ├── Resources/
│       │   │   ├── VersionResource.php
│       │   │   └── VersionResource/
│       │   │       ├── Pages/
│       │   │       │   ├── ListVersions.php
│       │   │       │   ├── ViewVersion.php
│       │   │       │   └── EditVersion.php
│       │   │       │
│       │   │       ├── Actions/
│       │   │       │   ├── CreateVersionAction.php
│       │   │       │   ├── PublishVersionAction.php
│       │   │       │   └── CompareVersionAction.php
│       │   │       │
│       │   │       └── RelationManagers/
│       │   │           └── VersionsRelationManager.php
│       │   │
│       │   ├── Pages/
│       │   │   ├── VersionDashboard.php
│       │   │   ├── CurrentVersions.php
│       │   │   └── SupersededVersions.php
│       │   │
│       │   └── Widgets/
│       │       ├── VersionStatsWidget.php
│       │       ├── PendingApprovalWidget.php
│       │       └── ExpiringVersionsWidget.php
│       │
│       └── Staff/
│           ├── Pages/
│           │   ├── CurrentVersions.php
│           │   └── VersionViewer.php
│           │
│           └── Widgets/
│               └── MyAccessibleVersionsWidget.php
│
├── Jobs/
│   ├── ExpireVersions.php
│   ├── SupersedeVersions.php
│   └── NotifyVersionStakeholders.php
│
├── Listeners/
│   ├── OnVersionPublished.php
│   ├── OnVersionApproved.php
│   └── UpdateCurrentVersionPointer.php
│
├── Models/
│   └── Version.php
│
├── Observers/
│   └── VersionObserver.php
│
├── Policies/
│   └── VersionPolicy.php
│
├── Services/
│   ├── VersionLifecycleService.php
│   ├── VersionResolverService.php
│   ├── SemanticVersionService.php
│   ├── VersionComparisonService.php
│   ├── VersionStorageService.php
│   └── VersionIntegrityService.php
│
├── Traits/
│   └── HasVersions.php
│
└── Enums/
    ├── VersionStatus.php
    ├── VersionIncrementType.php
    ├── ContentDriverType.php
    └── VersionEventType.php


```

```text
DATABASE

versions
├── id
├── versionable_type
├── versionable_id

├── revision                (internal sequential ID)
├── major
├── minor
├── patch

├── status                  (draft, review, approved, published, superseded, archived)

├── effective_from
├── effective_until

├── content_driver          (file | url | git | s3 | db)
├── content_reference

├── change_reason
├── change_summary

├── checksum (optional, computed not trusted)

├── approved_by
├── created_by

├── created_at
└── updated_at


OPTIONAL

version_links
├── id
├── version_id
├── related_version_id
├── relation_type (depends_on | supersedes | references | derived_from)
└── timestamps


```

```text
MODEL RELATIONSHIPS

Version
├── morphTo(versionable)
├── belongsTo(createdBy)
├── belongsTo(approvedBy)
└── hasMany(links)

VersionLink
├── belongsTo(version)
├── belongsTo(relatedVersion)


```

```text
TRAIT

HasVersions
├── versions()
├── currentVersion()
├── latestVersion()
├── publishedVersions()
├── draftVersions()
├── createVersion()
├── publishVersion()
├── approveVersion()
├── archiveVersion()
├── supersedeVersion()

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
Published
  ↓
Superseded
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
```php
<?php

namespace App\Trees\Ver\Http\UI\Admin\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;

class CreateVersionAction
{
    public static function make(): Action
    {
        return Action::make('createVersion')
            ->label('Add Version')
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->slideOver()
            ->form(self::form())
            ->action(fn (array $data, Model $record) =>
                self::handle($record, $data)
            );
    }

    protected static function form(): array
    {
        return [

            Radio::make('increment_type')
                ->label('Version Type')
                ->options([
                    'major' => 'Major (X.0.0)',
                    'minor' => 'Minor (0.X.0)',
                    'patch' => 'Patch (0.0.X)',
                ])
                ->required(),

            DatePicker::make('effective_from')
                ->label('Effective From')
                ->required(),

            DatePicker::make('effective_until')
                ->label('Effective Until')
                ->default(now()->addYear()),

            Radio::make('content_type')
                ->label('Content Source')
                ->options([
                    'file' => 'Upload File',
                    'url' => 'External URL',
                ])
                ->reactive()
                ->required(),

            FileUpload::make('file')
                ->label('Upload File')
                ->visible(fn ($get) => $get('content_type') === 'file')
                ->directory('versions')
                ->required(fn ($get) => $get('content_type') === 'file'),

            TextInput::make('url')
                ->label('Content URL')
                ->url()
                ->visible(fn ($get) => $get('content_type') === 'url')
                ->required(fn ($get) => $get('content_type') === 'url'),

            Textarea::make('change_reason')
                ->label('Change Reason (ISO Required)')
                ->required()
                ->maxLength(1000),

            Textarea::make('change_summary')
                ->label('Change Summary')
                ->maxLength(1000),
        ];
    }

    protected static function handle(Model $record, array $data): void
    {
        $latest = $record->versions()
            ->latest('revision')
            ->first();

        // Base version fallback
        $major = $latest?->major ?? 0;
        $minor = $latest?->minor ?? 0;
        $patch = $latest?->patch ?? 0;
        $revision = ($latest?->revision ?? 0) + 1;

        // Increment logic
        [$major, $minor, $patch] = match ($data['increment_type']) {
            'major' => [$major + 1, 0, 0],
            'minor' => [$major, $minor + 1, 0],
            'patch' => [$major, $minor, $patch + 1],
        };

        // Content resolution
        $contentDriver = $data['content_type'];
        $contentReference = $contentDriver === 'file'
            ? $data['file']
            : $data['url'];

        $record->versions()->create([
            'revision' => $revision,

            'major' => $major,
            'minor' => $minor,
            'patch' => $patch,

            'status' => 'draft',

            'effective_from' => $data['effective_from'],
            'effective_until' => $data['effective_until'] ?? now()->addYear(),

            'content_driver' => $contentDriver,
            'content_reference' => $contentReference,

            'change_reason' => $data['change_reason'],
            'change_summary' => $data['change_summary'] ?? null,

            'created_by' => auth()->id(),
        ]);
    }
}
```