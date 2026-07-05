<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Generic lifecycle status list (Active, Inactive, Pending, Suspended,
     * Expired, Draft, Approved, Rejected, Archived...). `applies_to` lets us
     * filter which module a status is relevant for in dropdown queries,
     * while still being one physical table per rule #4 (a "status" concept,
     * not a "list of unrelated things" concept).
     */
    public function up(): void
    {
        Schema::create('lu_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();     // active, suspended, pending...
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->string('applies_to', 60)->default('general'); // users, members, disciplinary_cases...
            $table->string('color_hex', 7)->default('#006400');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('applies_to');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_statuses');
    }
};
