<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_results_certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('election_cycle_year');
            $table->foreignId('position_id')->constrained('lu_elected_positions');
            $table->foreignId('winning_nomination_id')->constrained('election_nominations');
            $table->boolean('was_tie_broken_by_lots')->default(false);
            $table->date('certified_on');
            $table->string('commission_member_1_name');
            $table->string('commission_member_2_name');
            $table->string('commission_member_3_name');
            $table->boolean('filed_with_secretary_general')->default(false);
            $table->date('handover_date')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/approved/archived

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_results_certifications');
    }
};
