<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            // Optional link to a login account — a member may exist in the
            // Registry before (or without) ever getting portal credentials.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // MEM-001 — Written Application (Art. 14-15)
            $table->string('full_name');
            $table->string('national_id')->unique();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('statement_of_commitment')->nullable();
            $table->foreignId('category_id')->constrained('lu_membership_categories');
            $table->foreignId('fee_tier_id')->constrained('lu_fee_tiers');
            $table->foreignId('payment_method_id')->nullable()->constrained('lu_payment_methods');
            $table->date('application_date');
            $table->boolean('hardship_payment_plan')->default(false); // 6-month minimum plan, Art. 14

            // MEM-002 — Membership Acknowledgement (Art. 15)
            $table->timestamp('acknowledged_at')->nullable();
            $table->date('entry_date')->nullable(); // date entered into Master Registry

            // Registry lifecycle (Art. 13(3))
            $table->foreignId('status_id')->constrained('lu_statuses'); // member_active/inactive/suspended/terminated
            $table->date('status_since')->nullable();
            $table->text('status_reason')->nullable();

            // Fee waiver flag mirrors latest MEM-005 decision, for quick registry filtering
            $table->boolean('has_active_fee_waiver')->default(false);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
