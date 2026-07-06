<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('disciplinary_cases')->cascadeOnDelete();
            $table->foreignId('notice_type_id')->constrained('lu_notice_types');
            $table->date('issued_on');
            $table->date('response_deadline')->nullable(); // >= 14 days from issued_on
            $table->date('hearing_date')->nullable();
            $table->string('hearing_venue')->nullable();
            $table->text('allegations_summary')->nullable();
            $table->boolean('respondent_acknowledged')->default(false);
            $table->date('respondent_response_received_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/acknowledged/responded/expired

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinary_notices');
    }
};
