<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_clubs', function (Blueprint $table) {
            $table->id();

            // FAN-001 — Recognition Application (Art. 177)
            $table->string('proposed_name');
            $table->unsignedSmallInteger('founding_members_count'); // minimum 15, Art. 177
            $table->text('objectives_statement');
            $table->boolean('charter_provided')->default(false);
            $table->string('chairperson_name');
            $table->string('secretary_name');
            $table->string('treasurer_name');
            $table->boolean('code_of_conduct_commitment')->default(false);
            $table->string('designated_account_reference')->nullable(); // bank or mobile money evidence
            $table->date('application_date');

            // FAN-002 — Certificate of Recognition (Art. 177)
            $table->string('certificate_number')->nullable()->unique();
            $table->date('recognized_on')->nullable();
            $table->string('signed_by_president_name')->nullable();

            // Registration fee (Art. 183)
            $table->date('registration_fee_due_on')->nullable(); // recognized_on + 30 days
            $table->boolean('registration_fee_paid')->default(false);

            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected/deregistered

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_clubs');
    }
};
