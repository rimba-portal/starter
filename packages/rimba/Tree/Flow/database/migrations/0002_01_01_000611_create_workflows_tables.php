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

        Schema::create('workflow_blueprints', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->foreignId('org_teams_id')->nullable()->constrained('org_teams');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('workflow_nodes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            $table->foreignId('work_package_id')->nullable()->constrained('work_packages');
            $table->string('name');
            $table->string('type')->index();
            $table->timestamps();
        });
        Schema::create('workflow_transitions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            $table->foreignId('from_node_id')->constrained('workflow_nodes');
            $table->foreignId('to_node_id')->constrained('workflow_nodes');
            $table->string('name')->nullable();
            $table->string('action')->nullable();
            $table->text('condition')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflowblueprints');
            // $table->string('trackable_id')->nullable();
            // $table->string('trackable_type')->nullable();
            $table->string('status')->default('active');
            $table->morphs('trackable');
            $table->timestamps();
        });
        Schema::create('workflow_node_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflowinstances');
            $table->foreignId('workflow_node_id')->constrained('workflow_nodes');
            $table->timestamp('activated_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        Schema::create('workflow_transition_instances', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflowinstances');
            $table->foreignId('workflow_transition_id')->constrained('workflow_transitions');
            $table->timestamp('executed_at');
            $table->foreignId('executed_by_id')->nullable()->constrained('users');
            $table->timestamps();
        });

        Schema::create('workflow_blueprint_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_blueprint_id')->constrained('workflow_blueprints')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();

            $table->unique(['workflow_blueprint_id', 'role_id',], 'workflow_blueprint_role_unique');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_blueprint_role');
        Schema::dropIfExists('workflow_transition_instances');
        Schema::dropIfExists('workflow_node_instances');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_nodes');
        Schema::dropIfExists('workflow_blueprints');
    }
};
