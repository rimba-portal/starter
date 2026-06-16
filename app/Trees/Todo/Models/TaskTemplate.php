<?php

declare(strict_types=1);

namespace App\Trees\Todo\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Models\Role;

#[Fillable([
    'role_id',
    'name',
])]
class TaskTemplate extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'role_id' => 'integer',
        ];
    }

    public function taskListTemplateItems(): HasMany
    {
        return $this->hasMany(TaskListTemplateItem::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
