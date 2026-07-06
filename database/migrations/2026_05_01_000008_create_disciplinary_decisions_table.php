<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('disciplinary_cases')->cascadeOnDelete();
            $table->date('decision_date'); // within 7 days of hearing/deliberation, Art. 1158
            $table->text('case_summary');
            $table->text('findings_of_fact');
            $table->text('rules_violated');
            $table->text('reasoning');
            $table->foreignId('sanction_id')->nullable()->constrained('lu_disciplinary_sanctions');
            $table->date('sanction_effective_date')->nullable();
            $table->date('appeal_deadline')->nullable();
            $table->boolean('communicated_to_respondent')->default(false);
            $table->boolean('communicated_to_executive_organ')->default(false);
            $table->boolean('recorded_by_secretary_general')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/approved/appealed/final

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_decisions');
    }
};
