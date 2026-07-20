<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id',
    'module_id',
    'sequence',
    'attributes',
])]
class CourseModule extends Model
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
            'course_id' => 'integer',
            'module_id' => 'integer',
            'sequence' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public static function seedMappings(): array
    {
        return [
            'course_code' => fn (string $value) => [
                'course_id' => Course::query()
                    ->where('code', $value)
                    ->firstOrFail()
                    ->id,
            ],

            'module_code' => fn (string $value) => [
                'module_id' => Module::query()
                    ->where('code', $value)
                    ->firstOrFail()
                    ->id,
            ],
        ];
    }
}