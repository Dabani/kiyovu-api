<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->unsignedBigInteger('amount_rwf');
            $table->foreignId('expenditure_tier_id')->constrained('lu_expenditure_tiers'); // auto-derived from amount
            $table->string('payee_name');
            $table->date('payment_date');
            $table->string('authorized_by_ceo_name')->nullable();
            $table->string('co_signed_by_treasurer_name')->nullable(); // required for significant+
            $table->boolean('executive_organ_resolution')->default(false); // required for major
            $table->boolean('ga_resolution')->default(false); // required for capital
            $table->string('supporting_documentation_ref')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/paid/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_authorizations');
    }
};
