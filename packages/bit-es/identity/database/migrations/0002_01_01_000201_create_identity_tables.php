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

        Schema::create('identity_profiles', function (Blueprint $table) {
            $table->id();
            $table->morphs('personable');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('identity_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identity_profile_id')->constrained()->cascadeOnDelete();
            $table->string('factor_type');
            $table->longText('value');
            $table->json('metadata')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('identity_attempts', function (Blueprint $table) {

            $table->id();
            $table->foreignId('identity_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('factor');
            $table->string('status');
            $table->json('context')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_attempts');
        Schema::dropIfExists('identity_credentials');
        Schema::dropIfExists('identity_profiles');
    }
};
