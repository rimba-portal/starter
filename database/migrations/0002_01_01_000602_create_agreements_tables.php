<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('agreement_types', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->longText('template')->nullable();
            $table->json('public_schema')->nullable();
            $table->json('confidential_schema')->nullable();
            $table->json('notify')->nullable();
            $table->integer('expiry_notify_days')->default(30);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->foreignId('workflow_id')->nullable()->constrained();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('agreement_type');
            $table->string('contract_no')->nullable();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'expired', 'terminated', 'archived'])->default('draft');
            $table->json('terms')->nullable();
            $table->json('meta')->nullable();
            // $table->morphs('contractable');
            $table->timestamps();
        });
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agreement_id')->constrained('agreements');
            $table->string('role')->nullable();
            $table->boolean('is_signatory')->default(false);
            $table->boolean('notify_on_expiry')->default(true);
            $table->json('meta')->nullable();
            $table->morphs('party');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_parties');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_types');
    }
};
