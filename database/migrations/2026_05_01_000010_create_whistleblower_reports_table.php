<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whistleblower_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('lu_whistleblower_categories');
            $table->boolean('is_anonymous')->default(false);
            $table->string('reporter_name')->nullable(); // null when anonymous
            $table->date('reported_on');
            $table->text('description');
            $table->date('receipt_acknowledged_on')->nullable(); // within 7 days
            $table->date('initial_assessment_completed_on')->nullable(); // within 30 days
            $table->string('referred_to')->nullable(); // CRO / Board / Audit Organ / Rwandan authorities
            $table->boolean('retaliation_protection_confirmed')->default(true); // Art. 274/34
            $table->foreignId('related_disciplinary_case_id')->nullable()->constrained('disciplinary_cases')->nullOnDelete();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/under_review/referred/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whistleblower_reports');
    }
};
