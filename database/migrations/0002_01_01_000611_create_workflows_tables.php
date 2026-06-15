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

        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->foreignId('start_step_id')->nullable()->constrained('workflow_steps');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained();
            $table->enum('type', ["start", "process", "decision", "end"])->default('process');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('requires_tasks')->default(false);
            $table->boolean('requires_decision')->default(false);
            $table->boolean('is_automatic')->default(false);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained();
            $table->foreignId('from_step_id')->constrained('workflow_steps');
            $table->foreignId('to_step_id')->constrained('workflow_steps');
            $table->string('name')->nullable();
            $table->json('conditions')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_step_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_step_id')->constrained();
            $table->foreignId('task_template_id')->nullable()->constrained();
            $table->foreignId('task_list_template_id')->nullable()->constrained();
            $table->boolean('is_required')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained();
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps');
            $table->enum('status', ["active", "completed", "cancelled"])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('attributes')->nullable();
            $table->morphs('ref');
            $table->timestamps();
        });
        Schema::create('workflow_instance_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained();
            $table->foreignId('workflow_step_id')->constrained();
            $table->enum('status', ["pending", "active", "completed", "skipped"])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained();
            $table->foreignId('workflow_step_id')->constrained();
            $table->foreignId('user_id')->constrained('staff');
            $table->enum('decision', ["approve", "reject", "request_changes"]);
            $table->text('comment')->nullable();
            $table->timestamp('decided_at');
            $table->json('attributes')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_decisions');
        Schema::dropIfExists('workflow_instance_steps');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_step_tasks');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflows');
    }
};
