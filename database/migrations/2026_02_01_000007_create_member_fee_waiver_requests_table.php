<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_fee_waiver_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('requested_on');
            $table->text('hardship_justification');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected
            $table->date('reviewed_on')->nullable();
            $table->date('valid_until')->nullable(); // waivers must be renewed annually, Art. 15(3)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_fee_waiver_requests');
    }
};
