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

        Schema::create('task_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->morphs('ref');
            $table->timestamps();
        });
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('staff_id')->nullable()->constrained();
            $table->foreignId('task_list_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->morphs('ref');
            $table->timestamps();
        });

        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('task_list_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('task_list_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_list_template_id')->constrained();
            $table->foreignId('task_template_id')->constrained();
            $table->foreignId('depends_on_id')->nullable()->constrained('task_list_template_items');
            $table->timestamps();
        });
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained();
            $table->foreignId('role_id')->nullable()->constrained();
            $table->foreignId('staff_id')->nullable()->constrained();
            $table->foreignId('assigned_by')->nullable()->constrained('staff');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
        Schema::dropIfExists('task_list_template_items');
        Schema::dropIfExists('task_list_templates');
        Schema::dropIfExists('task_templates');
        Schema::dropIfExists('task_lists');
        Schema::dropIfExists('tasks');
    }
};
