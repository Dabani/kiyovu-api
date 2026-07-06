<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_conflict_of_interest_declarations', function (Blueprint $table) {
            $table->id();
            $table->string('declarant_name');
            $table->foreignId('position_id')->nullable()->constrained('lu_hq_positions');
            $table->date('declaration_date');
            $table->text('conflict_description');
            $table->boolean('recusal_required')->default(false);
            $table->string('reviewed_by_name')->nullable();
            $table->date('next_annual_update_due')->nullable(); // Art. 144 — updated annually
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_conflict_of_interest_declarations');
    }
};
