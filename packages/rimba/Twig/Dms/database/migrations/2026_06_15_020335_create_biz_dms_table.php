<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qms_documents', function (Blueprint $table): void {
            $table->id();

            // 1. QMS Hierarchy & Relationships
            // $table->foreignId('qms_tier_id')->constrained('qms_tiers')->onDelete('restrict');
            $table->foreignId('parent_id')->nullable()->constrained('qms_documents')->onDelete('set null'); // SOP -> WI link

            // 2. Core Identification & Taxonomy
            $table->string('doc_number')->unique(); // Unique identifier (e.g., SOP-QA-001)
            $table->string('title');
            $table->string('site_location')->nullable(); // Multi-plant/site traceability

            // 3. Strict Lifecycle & State Management
            // States: draft, in_review, active, obsolete, archived
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_controlled')->default(true); // Controlled copies vs uncontrolled documents

            // 4. Structural Stakeholder Ownership
            $table->foreignId('team_id')->constrained('org_teams')->onDelete('restrict'); // Process Owner (Ultimate Responsible Party)
            $table->foreignId('author_id')->constrained('staff')->onDelete('restrict'); // Author/Preparer

            // 5. Versioning Pointer (Polymorphic target link)
            $table->foreignId('current_version_id')->nullable()->constrained('versions')->onDelete('set null');

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
            $table->index(['team_id', 'status']);
            $table->index(['next_review_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_documents');
    }
};
