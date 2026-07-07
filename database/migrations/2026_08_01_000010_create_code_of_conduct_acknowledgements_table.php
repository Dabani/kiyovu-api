<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('code_of_conduct_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->string('signatory_name');
            $table->foreignId('signatory_type_id')->constrained('lu_signatory_types');
            $table->foreignId('position_id')->nullable()->constrained('lu_hq_positions');
            $table->date('signed_date');
            $table->date('safeguarding_training_completed_on')->nullable(); // within 30 days of starting, Art. 211
            $table->date('safeguarding_certification_expiry')->nullable(); // annual renewal
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/signed/expired

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('code_of_conduct_acknowledgements');
    }
};
