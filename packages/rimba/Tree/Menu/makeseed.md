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
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table): void {

            $table->id();


            /*
             * BPM Category
             *
             * Enterprise
             * People
             * Market
             * Supply
             * Operate
             * Technology
             * Knowledge
             * Source
             */
            $table->string('category');


            /*
             * Group
             *
             * Example:
             *
             * Finance
             * HR
             * Production
             * Documents
             */
            $table->string('group')
                ->nullable();


            /*
             * Display
             */
            $table->string('name');

            $table->string('slug')
                ->unique();


            $table->string('icon')
                ->nullable();


            /*
             * Tree structure
             *
             * Category
             *   |
             *   Group
             *       |
             *       Menu Item
             */
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('menus')
                ->nullOnDelete();


            /*
             * Permission checking
             */
            $table->string('permission')
                ->nullable();


            /*
             * Filament panel
             *
             * admin
             * staff
             */
            $table->string('panel')
                ->nullable();


            /*
             * Behaviour
             */
            $table->boolean('enabled')
                ->default(true);


            $table->boolean('visible')
                ->default(true);


            $table->boolean('open_new_tab')
                ->default(false);



            /*
             * Ordering
             */
            $table->unsignedInteger('sort')
                ->default(0);



            $table->timestamps();



            $table->index('category');

            $table->index('group');

            $table->index('parent_id');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
```
```php
<?php

declare(strict_types=1);


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Menu extends Model
{

    protected $fillable = [

        'category',

        'group',

        'name',

        'slug',

        'icon',

        'parent_id',

        'permission',

        'panel',

        'enabled',

        'visible',

        'open_new_tab',

        'sort',

    ];



    protected $casts = [

        'enabled' => 'boolean',

        'visible' => 'boolean',

        'open_new_tab' => 'boolean',

    ];



    /*
     |--------------------------------------------------------------------------
     | Version
     |--------------------------------------------------------------------------
     */


    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable'
        );
    }



    public function activeVersion()
    {
        return $this->morphOne(
            Version::class,
            'versionable'
        )
        ->where('status', 'released')
        ->latestOfMany('released_at');
    }



    /*
     |--------------------------------------------------------------------------
     | Menu Tree
     |--------------------------------------------------------------------------
     */


    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Menu::class,
            'parent_id'
        );
    }



    public function children(): HasMany
    {
        return $this->hasMany(
            Menu::class,
            'parent_id'
        )
        ->orderBy('sort');
    }



    /*
     |--------------------------------------------------------------------------
     | Scope
     |--------------------------------------------------------------------------
     */


    public function scopeVisible($query)
    {
        return $query->where('visible', true)
            ->where('enabled', true);
    }


}
```
```text
