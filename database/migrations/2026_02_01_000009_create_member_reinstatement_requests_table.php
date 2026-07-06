<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_reinstatement_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('submitted_on');
            $table->date('suspension_completed_on');
            $table->text('compliance_evidence');
            $table->text('cro_recommendation')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(EO abs. majority)/rejected
            $table->date('decided_on')->nullable();
            $table->text('ongoing_conditions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_reinstatement_requests');
    }
};
