<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_club_deregistration_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_club_id')->constrained('fan_clubs')->cascadeOnDelete();
            $table->text('grounds');
            $table->date('issued_on');
            $table->date('remedy_deadline'); // issued_on + 30 days, Step 1
            $table->boolean('remedied')->default(false);

            // Step 2 — Explanation
            $table->date('explanation_invited_on')->nullable();
            $table->text('explanation_received')->nullable();

            // Step 3 — Decision
            $table->date('executive_organ_decision_date')->nullable();
            $table->boolean('deregistration_decided')->nullable();
            $table->text('decision_reasons')->nullable();

            // Step 4 — Appeal
            $table->boolean('appealed_to_ga')->default(false);
            $table->date('appeal_filed_on')->nullable();
            $table->boolean('ga_appeal_upheld')->nullable();

            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(deregistered)/rejected(remedied)

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_club_deregistration_warnings');
    }
};
