<?php

namespace App\Business\Lms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'module_id',
        'staff_id',
        'quiz_attempt_id',
        'evaluation_id',
        'issued_by',
        'status',
        'issued_at',
        'expires_at',
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
            'module_id' => 'integer',
            'staff_id' => 'integer',
            'quiz_attempt_id' => 'integer',
            'evaluation_id' => 'integer',
            'issued_by' => 'integer',
            'issued_at' => 'timestamp',
            'expires_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(\App\Trees\Organization\Models\Staff::class);
    }

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
