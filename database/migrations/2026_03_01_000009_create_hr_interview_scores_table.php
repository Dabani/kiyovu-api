<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_interview_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('recruitment_candidates')->cascadeOnDelete();
            $table->date('interview_date');
            $table->decimal('technical_competence_score', 4, 1); // 0-10
            $table->decimal('values_alignment_score', 4, 1);     // 0-10
            $table->decimal('position_specific_score', 4, 1);    // 0-10
            $table->text('interviewer_notes')->nullable();
            $table->boolean('recommended_to_proceed')->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_interview_scores');
    }
};
