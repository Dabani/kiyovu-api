<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_resignations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->date('submitted_on');
            $table->text('resignation_letter');
            $table->boolean('outstanding_obligations')->default(false);
            $table->text('outstanding_obligations_notes')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(GA)/rejected
            $table->date('ga_approval_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_resignations');
    }
};
