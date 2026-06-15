<?php

namespace App\Trees\Todo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskListTemplateItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_list_template_id',
        'task_template_id',
        'depends_on_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'task_list_template_id' => 'integer',
            'task_template_id' => 'integer',
            'depends_on_id' => 'integer',
        ];
    }

    public function dependents(): HasMany
    {
        return $this->hasMany(TaskListTemplateItem::class);
    }

    public function taskListTemplate(): BelongsTo
    {
        return $this->belongsTo(TaskListTemplate::class);
    }

    public function taskTemplate(): BelongsTo
    {
        return $this->belongsTo(TaskTemplate::class);
    }

    public function dependsOn(): BelongsTo
    {
        return $this->belongsTo(TaskListTemplateItem::class);
    }
}
