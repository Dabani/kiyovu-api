<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('safeguarding_concern_reports', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_anonymous')->default(false);
            $table->string('reporter_name')->nullable(); // null when anonymous
            $table->date('concern_date');
            $table->text('description');
            $table->string('subject_reference')->nullable(); // deliberately not a full identity field — kept minimal/sensitive
            $table->date('receipt_acknowledged_on')->nullable(); // within 24 hours, Art. 212
            $table->date('initial_assessment_completed_on')->nullable(); // within 72 hours
            $table->boolean('risk_identified')->nullable();
            $table->date('reported_to_authorities_on')->nullable(); // within 24 hours of assessment if risk identified
            $table->boolean('accused_suspended_from_minors_contact')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/under_review/referred/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safeguarding_concern_reports');
    }
};
