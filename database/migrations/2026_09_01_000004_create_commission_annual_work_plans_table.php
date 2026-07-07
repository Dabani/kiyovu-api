<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commission_annual_work_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pillar_id')->constrained('lu_commission_pillars');
            $table->unsignedSmallInteger('plan_year');
            $table->text('objectives');
            $table->date('submitted_on');
            $table->date('executive_organ_approved_on')->nullable(); // due before start of year, Art. 99
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pillar_id', 'plan_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commission_annual_work_plans');
    }
};
