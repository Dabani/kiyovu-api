<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_kpi_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pillar_id')->constrained('lu_commission_pillars');
            $table->unsignedSmallInteger('plan_year');
            $table->text('kpis_established'); // set at start of year by Executive Organ in consultation with Commission
            $table->date('established_on');
            $table->text('mid_year_review_notes')->nullable();
            $table->date('mid_year_reviewed_on')->nullable();
            $table->text('year_end_review_notes')->nullable();
            $table->date('year_end_reviewed_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/mid_year_reviewed/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pillar_id', 'plan_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_kpi_reports');
    }
};
