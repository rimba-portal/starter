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

        Schema::create('attribute_definitions', function (Blueprint $table): void {
            $table->id();
            $table->string('family'); // personnel, asset, area
            $table->string('group');  // identity, skills, security, etc.
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('applies_to')->nullable();
            $table->string('example_key')->nullable();
            $table->string('example_value')->nullable();
            $table->boolean('has_options')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_abac')->default(false);
            $table->boolean('is_system')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['family', 'group']);
            $table->index(['family', 'key']);
        });

        Schema::create('attribute_options', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attribute_definition_id')->constrained('attribute_definitions')->cascadeOnDelete();
            $table->string('value');
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['attribute_definition_id', 'value']);
        });

        // for consumption of users, staffs (actual), job_posts (defined)
        Schema::create('person_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'gender', 'dob', 'phone'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });
        // for consumption of assets, equipment, (actual,defined)
        Schema::create('location_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'dimensions', 'type', 'location'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });
        // for consumption of assets, equipment, (actual,defined)
        Schema::create('thing_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'dimensions', 'type', 'location'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thing_attributes');
        Schema::dropIfExists('location_attributes');
        Schema::dropIfExists('person_attributes');
        Schema::dropIfExists('attribute_options');
        Schema::dropIfExists('attribute_definitions');
    }
};
