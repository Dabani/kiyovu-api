<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('petty_cash_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedInteger('amount_rwf'); // capped at 50,000 by validation
            $table->string('department');
            $table->string('requested_by_name');
            $table->string('departmental_head_name');
            $table->date('voucher_date');
            $table->boolean('receipt_attached')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/reimbursed/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('petty_cash_vouchers');
    }
};
