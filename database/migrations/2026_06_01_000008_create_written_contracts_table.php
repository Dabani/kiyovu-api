<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('written_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_type_id')->constrained('lu_contract_types');
            $table->string('counterparty_name');
            $table->text('description');
            $table->unsignedBigInteger('value_rwf')->nullable();
            $table->unsignedBigInteger('monthly_value_rwf')->nullable(); // for partnership tiers, Art. 1013
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('executive_organ_approved')->default(false);
            // Art. 1013 — partnerships: >10M/month or 120M/year notified to GA; >=50M/month requires prior GA approval
            $table->boolean('ga_notified')->default(false);
            $table->boolean('ga_approval_required')->default(false);
            $table->boolean('ga_approved')->default(false);
            $table->date('signed_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/pending/approved/signed/terminated

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('written_contracts');
    }
};
