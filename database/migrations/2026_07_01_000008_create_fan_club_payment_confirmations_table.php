<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_club_payment_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_club_id')->constrained('fan_clubs')->cascadeOnDelete();
            $table->date('contribution_month'); // stored as first-of-month
            $table->unsignedInteger('amount_rwf');
            $table->string('payment_reference');
            $table->date('submitted_on'); // due by the 20th of the following month
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/verified/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['fan_club_id', 'contribution_month'], 'fcpc_fan_club_month_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_club_payment_confirmations');
    }
};
