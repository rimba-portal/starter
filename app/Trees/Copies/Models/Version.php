<?php

namespace App\Trees\Copies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Version extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'versionable_id',
        'created_by',
        'approved_by',
        'published_by',
        'status',
        'content_type',
        'content_path',
        'major',
        'minor',
        'patch',
        'version',
        'change_summary',
        'change_notes',
        'is_menu',
        'approved_at',
        'published_at',
        'effective_from',
        'effective_to',
        'attributes',
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
            'versionable_id' => 'integer',
            'created_by' => 'integer',
            'approved_by' => 'integer',
            'published_by' => 'integer',
            'is_menu' => 'boolean',
            'approved_at' => 'timestamp',
            'published_at' => 'timestamp',
            'effective_from' => 'timestamp',
            'effective_to' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function versionApprovals(): HasMany
    {
        return $this->hasMany(VersionApproval::class);
    }

    public function relations(): HasMany
    {
        return $this->hasMany(VersionRelation::class);
    }

    public function relatedRelations(): HasMany
    {
        return $this->hasMany(VersionRelation::class);
    }

    public function versionable(): BelongsTo
    {
        return $this->belongsTo(Versionable::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }
}
