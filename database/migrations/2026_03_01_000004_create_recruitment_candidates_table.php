<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitment_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('vacancy_title'); // e.g. "Financial Director — 2026 Recruitment"
            $table->foreignId('position_id')->constrained('lu_hq_positions');
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('application_date');
            $table->date('vacancy_published_on')->nullable();
            $table->date('vacancy_closing_date')->nullable(); // Art. 138 — min. 14 day publication

            // HR-005 — Shortlisting Criteria & Scores (Art. 138)
            $table->boolean('shortlisted')->default(false);
            $table->decimal('shortlist_score', 5, 2)->nullable();
            $table->text('shortlisting_notes')->nullable();
            $table->date('shortlisted_on')->nullable();

            $table->foreignId('status_id')->constrained('lu_statuses'); // applied/shortlisted/interviewed/recommended/appointed/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['position_id', 'status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitment_candidates');
    }
};
