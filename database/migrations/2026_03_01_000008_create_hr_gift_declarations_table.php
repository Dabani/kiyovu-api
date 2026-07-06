<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hr_gift_declarations', function (Blueprint $table) {
            $table->id();
            $table->string('declarant_name');
            $table->foreignId('position_id')->nullable()->constrained('lu_hq_positions');
            $table->string('gift_description');
            $table->unsignedInteger('estimated_value_rwf');
            $table->date('date_received');
            $table->date('declared_on'); // must be within 5 days of receipt, Art. 128
            $table->foreignId('disposition_id')->nullable()->constrained('lu_gift_dispositions');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/rejected

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hr_gift_declarations');
    }
};
