// database/migrations/xxxx_xx_xx_000002_create_qms_documents_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('qms_documents', function (Blueprint $table) {
            $table->id();
            
            // 1. QMS Hierarchy & Relationships
            $table->foreignId('qms_tier_id')->constrained('qms_tiers')->onDelete('restrict');
            $table->foreignId('parent_id')->nullable()->constrained('qms_documents')->onDelete('set null'); // SOP -> WI link
            
            // 2. Core Identification & Taxonomy
            $table->string('doc_number')->unique(); // Unique identifier (e.g., SOP-QA-001)
            $table->string('title');
            $table->string('department'); // e.g., Production, Quality Assurance, Logistics
            $table->string('site_location')->nullable(); // Multi-plant/site traceability
            
            // 3. Strict Lifecycle & State Management
            // States: draft, in_review, active, obsolete, archived
            $table->string('status', 20)->default('draft')->index(); 
            $table->boolean('is_controlled')->default(true); // Controlled copies vs uncontrolled documents
            
            // 4. Structural Stakeholder Ownership
            $table->foreignId('owner_id')->constrained('users')->onDelete('restrict'); // Process Owner (Ultimate Responsible Party)
            $table->foreignId('author_id')->constrained('users')->onDelete('restrict'); // Author/Preparer
            
            // 5. Versioning Pointer (Polymorphic target link)
            $table->foreignId('current_version_id')->nullable()->constrained('qms_versions')->onDelete('set null');
            
            // 6. QMS Regulatory / Compliance Metadata
            $table->string('security_classification')->default('internal'); // public, internal, restricted, highly_confidential
            $table->string('regulatory_impact')->nullable(); // e.g., ISO9001:2015 Clause 7.5, FDA 21 CFR Part 11
            $table->json('risk_assessment_tags')->nullable(); // Stores associated risk matrix IDs or tags
            
            // 7. Dynamic Retention & Expiry Controls (Crucial for Audits)
            $table->unsignedInteger('retention_period_years')->default(5); // How long the file must be legally preserved
            $table->date('effective_date')->nullable(); // The official rollout date to the shop floor
            $table->date('next_review_date')->nullable(); // Mandatory routine system review milestone
            
            // 8. Electronic Signature Validation Pointers
            $table->string('regulatory_hash', 64)->nullable(); // SHA-256 validation snapshot string for FDA compliance
            
            // 9. Standard Timestamps & soft deletes (Never hard-delete a QMS file!)
            $table->timestamps();
            $table->softDeletes(); 
            
            // Multi-column Indexes for lightning-fast DMS cross-referencing
            $table->index(['department', 'status']);
            $table->index(['next_review_date', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('qms_documents');
    }
};
// app/Models/QmsDocument.php
namespace App\Models;

use App\Traits\HasVersions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QmsDocument extends Model
{
    use HasVersions, SoftDeletes;

    protected $fillable = [
        'qms_tier_id', 'parent_id', 'doc_number', 'title', 'department', 
        'site_location', 'status', 'is_controlled', 'owner_id', 'author_id', 
        'current_version_id', 'security_classification', 'regulatory_impact', 
        'risk_assessment_tags', 'retention_period_years', 'effective_date', 
        'next_review_date', 'regulatory_hash'
    ];

    protected $casts = [
        'is_controlled' => 'boolean',
        'risk_assessment_tags' => 'array', // Handles flexible JSON tags dynamically
        'effective_date' => 'date',
        'next_review_date' => 'date',
        'retention_period_years' => 'integer'
    ];

    /**
     * Relationship to the specific structural QMS system hierarchy tier.
     */
    public function tier(): BelongsTo
    {
        return $this->belongsTo(QmsTier::class, 'qms_tier_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
