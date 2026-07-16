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

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('course_groups');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_group_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('course_group_id')->constrained();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('validity_days')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_team_id')->nullable()->constrained();
            $table->enum('type', ['document', 'video', 'link', 'other'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('material_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('pass_score')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->integer('score')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('evaluator_id')->nullable()->constrained('users');
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('quiz_attempt_id')->nullable()->constrained();
            $table->foreignId('evaluation_id')->nullable()->constrained();
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->enum('status', ['valid', 'expired', 'revoked'])->default('valid');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('material_modules');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('course_groups');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('courses');
    }
};
