<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_incident_reports', function (Blueprint $table) {
            $table->id();
            $table->date('incident_date');
            $table->string('event_description');
            $table->text('incident_description');
            $table->string('reported_by_name'); // Safety & Security Officer
            $table->date('reported_on'); // within 24 hours of the incident, Art. 129
            $table->boolean('coordinated_with_law_enforcement')->default(false);
            $table->boolean('coordinated_with_stadium_authorities')->default(false);
            $table->text('action_taken')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/under_review/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_incident_reports');
    }
};
