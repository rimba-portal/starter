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

        Schema::create('contract_confidentialities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->unique();
            $table->string('payload');
            $table->json('allowed_roles')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_confidentialities');
    }
};
