<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_inactive_status_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('requested_on');
            $table->text('reason');
            $table->date('effective_from');
            $table->date('max_end_date'); // effective_from + 2 years (Art. 17 cap)
            $table->date('reverted_to_active_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected/active(reverted)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_inactive_status_requests');
    }
};
