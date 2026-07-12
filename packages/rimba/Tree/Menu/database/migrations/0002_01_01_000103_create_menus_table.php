<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table): void {
            $table->id();

            // Enterprise taxonomy
            $table->string('category');          // Enterprise, People, Market, etc.
            $table->string('group')->nullable(); // Procurement, Payroll, Documents, etc.

            // Display
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();

            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            // Navigation hierarchy
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();

            // Access
            $table->string('permission')->nullable();
            $table->string('panel')->nullable();

            // Behaviour
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_active')->default(true);

            // Ordering
            $table->unsignedInteger('sort')->default(0);

            $table->timestamps();

            $table->index('category');
            $table->index('group');
            $table->index('parent_id');
            $table->index('sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
