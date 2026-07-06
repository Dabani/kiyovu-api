<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_information_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->text('information_requested'); // financial statements, commission reports, etc.
            $table->date('requested_on');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected
            $table->date('responded_on')->nullable();
            $table->text('response_notes')->nullable();
            $table->text('denial_reason')->nullable();
            $table->boolean('appealed_to_board')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_information_requests');
    }
};
