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

        Schema::create('org_corps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('uuid')->unique();
            $table->enum('type', ['company', 'government', 'vendor', 'institution'])->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('org_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_corp_id')->nullable()->constrained();
            $table->foreignId('parent_id')->nullable()->constrained('org_units');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('uuid')->unique();
            $table->string('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('org_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->constrained();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained();
            $table->enum('level', ['junior', 'mid', 'senior', 'lead', 'manager'])->nullable();
            $table->enum('status', ['open', 'filled', 'closed'])->default('open');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('job_roles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['approval', 'operation', 'admin', 'reporting'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('job_position_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_position_id')->constrained();
            $table->foreignId('job_role_id')->constrained();
            $table->timestamps();
        });
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('org_corp_id')->nullable()->constrained();
            $table->foreignId('org_unit_id')->nullable()->constrained();
            $table->enum('type', ['FTE', 'FTC', 'TPC', 'Intern'])->default('FTE');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->string('name')->nullable();
            $table->string('staff_no')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('staff_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('job_position_id')->constrained();
            $table->enum('assignment_type', ['primary', 'secondary', 'acting'])->default('primary');
            $table->enum('status', ['active', 'ended', 'pending'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['transfer', 'promotion', 'demotion', 'assignment', 'end_of_assignment']);
            $table->date('effective_date');
            $table->json('from')->nullable();
            $table->json('to')->nullable();
            $table->morphs('movable');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
        Schema::dropIfExists('staff_positions');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('job_position_roles');
        Schema::dropIfExists('job_roles');
        Schema::dropIfExists('job_positions');
        Schema::dropIfExists('org_teams');
        Schema::dropIfExists('org_units');
        Schema::dropIfExists('org_corps');
    }
};
