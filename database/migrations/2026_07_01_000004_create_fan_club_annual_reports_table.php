<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_club_annual_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_club_id')->constrained('fan_clubs')->cascadeOnDelete();
            $table->unsignedSmallInteger('report_year');
            $table->text('activities_summary');
            $table->text('membership_highlights')->nullable();
            $table->text('financial_highlights')->nullable();
            $table->date('submitted_on');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['fan_club_id', 'report_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_club_annual_reports');
    }
};
