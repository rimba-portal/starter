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

        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('offer_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('offer_categories');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('offer_category_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('offer_id')->constrained();
            $table->foreignId('offer_category_id')->constrained();
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
        Schema::dropIfExists('offer_category_assignments');
        Schema::dropIfExists('offer_categories');
        Schema::dropIfExists('offers');
    }
};
