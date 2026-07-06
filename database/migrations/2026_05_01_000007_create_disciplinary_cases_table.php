<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_source_id')->constrained('lu_disciplinary_case_sources');
            $table->string('respondent_name');
            $table->string('complainant_name')->nullable();
            $table->text('incident_description');
            $table->date('initiated_on');
            $table->date('receipt_acknowledged_on')->nullable(); // within 7 days, Art. 1140
            $table->date('preliminary_review_completed_on')->nullable(); // within 14 days
            $table->boolean('jurisdiction_confirmed')->nullable();
            $table->boolean('prima_facie_case')->nullable();
            $table->date('investigation_completed_on')->nullable(); // within 30 days of preliminary review
            $table->foreignId('status_id')->constrained('lu_statuses'); // intake/under_review/hearing_scheduled/decided/dismissed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_cases');
    }
};
