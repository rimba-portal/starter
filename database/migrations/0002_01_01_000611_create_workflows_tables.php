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
            $table->string('name');
            $table->string('key')->unique();
            $table->timestamps();
        });
        Schema::create('workflow_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); // start, process, decision, end
            $table->string('role_name')->nullable(); // ✅ Spatie role support
            $table->json('config')->nullable(); // ✅ dynamic config (forms, UI, rules, etc.)
            $table->timestamps();
        });
        Schema::create('workflow_edges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows')->cascadeOnDelete();
            $table->foreignId('from_node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->foreignId('to_node_id')->constrained('workflow_nodes')->cascadeOnDelete();
            $table->string('label')->nullable(); // Yes / No etc.
            $table->json('condition')->nullable(); // ✅ condition engine input
            $table->timestamps();
        });

        Schema::create('workflow_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained('workflows');
            $table->string('status'); // running, completed, cancelled
            $table->morphs('subject'); // ✅ polymorphic (fits your architecture)
            $table->timestamps();
        });
        Schema::create('workflow_node_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_instance_id')->constrained('workflow_instances')->cascadeOnDelete();
            $table->foreignId('workflow_node_id')->constrained('workflow_nodes');
            $table->string('status'); // pending, active, completed, skipped
            $table->json('data')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_node_instances');
        Schema::dropIfExists('workflow_instances');
        Schema::dropIfExists('workflow_edges');
        Schema::dropIfExists('workflow_nodes');
        Schema::dropIfExists('workflows');
    }
};
