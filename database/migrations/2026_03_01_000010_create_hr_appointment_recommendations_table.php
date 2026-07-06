<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_appointment_recommendations', function (Blueprint $table) {
            $table->id();
            $table->string('vacancy_title');
            $table->foreignId('position_id')->constrained('lu_hq_positions');
            $table->foreignId('recommended_candidate_id')->constrained('recruitment_candidates');
            $table->text('ranking_notes'); // full ranked list + reasoning, narrative per Art. 138
            $table->date('submitted_on');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(Exec Organ abs. majority)/rejected
            $table->date('executive_organ_decision_date')->nullable();
            $table->boolean('board_approval_required')->default(false); // "key appointments" per Art. 138
            $table->boolean('board_approved')->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_appointment_recommendations');
    }
};
