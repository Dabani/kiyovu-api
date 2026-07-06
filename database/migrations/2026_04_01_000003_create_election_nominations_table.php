<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_nominations', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('election_cycle_year');
            $table->foreignId('position_id')->constrained('lu_elected_positions');
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('candidate_full_name');
            $table->text('statement_of_intent'); // max 500 words, enforced client + server side
            $table->boolean('eligibility_declaration_signed')->default(false);
            $table->boolean('no_disqualifying_convictions_declared')->default(false);
            $table->boolean('legal_representative_limit_confirmed')->default(false); // <=2 other orgs
            $table->date('criminal_record_certificate_date')->nullable(); // President/VP only, within 3 months
            $table->date('nominated_on');
            $table->date('eligibility_determined_on')->nullable();
            $table->boolean('eligibility_approved')->nullable();
            $table->text('eligibility_notes')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['election_cycle_year', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_nominations');
    }
};
