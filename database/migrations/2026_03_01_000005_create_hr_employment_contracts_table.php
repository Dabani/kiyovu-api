<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_employment_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->nullable()->constrained('recruitment_candidates')->nullOnDelete();
            $table->string('employee_full_name');
            $table->foreignId('position_id')->constrained('lu_hq_positions');
            $table->foreignId('employment_type_id')->constrained('lu_employment_types');
            $table->text('duties_and_kpis');
            $table->text('qualifications_required')->nullable();
            $table->string('reporting_line')->nullable();
            $table->unsignedInteger('remuneration_rwf_monthly');
            $table->string('working_hours')->nullable();
            $table->date('term_start');
            $table->date('term_end')->nullable(); // null = indefinite
            $table->text('termination_grounds')->nullable();
            $table->boolean('confidentiality_acknowledged')->default(false);
            $table->date('ceo_signed_on')->nullable();
            $table->date('appointee_signed_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/pending/approved/active

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_employment_contracts');
    }
};
