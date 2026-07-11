```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table): void {
            $table->id();

            $table->morphs('versionable');

            $table->string('version');
            $table->unsignedInteger('major');
            $table->unsignedInteger('minor');
            $table->unsignedInteger('patch');

            $table->string('status')->default('draft');

            $table->string('content_type')->nullable();
            $table->text('content_url');

            $table->string('checksum')->nullable();

            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};
```
```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Models;

use Bites\Versioning\Builders\VersionBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'major',
    'minor',
    'patch',
    'status',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'notes',
])]
class Version extends Model
{
    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
}
```
```php

```
```php

```
```php

```
```php