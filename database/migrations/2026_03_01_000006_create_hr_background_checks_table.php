<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_background_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->nullable()->constrained('recruitment_candidates')->nullOnDelete();
            $table->string('subject_name'); // populated even when not tied to a candidate (existing staff/volunteers)
            $table->foreignId('position_id')->nullable()->constrained('lu_hq_positions');
            $table->boolean('role_involves_minors')->default(false);
            $table->date('consent_given_on');
            $table->text('verification_notes')->nullable();
            $table->foreignId('outcome_status_id')->constrained('lu_statuses'); // pending/approved(cleared)/rejected(flagged)
            $table->string('cleared_by_name')->nullable();
            $table->date('cleared_on')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_background_checks');
    }
};
