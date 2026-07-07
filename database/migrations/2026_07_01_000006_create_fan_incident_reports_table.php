<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_incident_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_club_id')->nullable()->constrained('fan_clubs')->nullOnDelete();
            $table->foreignId('incident_type_id')->constrained('lu_incident_types');
            $table->date('incident_date');
            $table->text('description');
            $table->date('documented_on'); // by Safety & Security Officer, within 24 hours
            $table->date('slo_report_due_on')->nullable(); // documented_on + 72 hours
            $table->text('slo_investigation_report')->nullable();
            $table->date('slo_report_submitted_on')->nullable();
            $table->boolean('adjudicated_by_fan_discipline_commission')->default(false); // else CRO
            $table->foreignId('sanction_id')->nullable()->constrained('lu_fan_sanctions');
            $table->boolean('referred_to_law_enforcement')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/under_review/adjudicated

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_incident_reports');
    }
};
