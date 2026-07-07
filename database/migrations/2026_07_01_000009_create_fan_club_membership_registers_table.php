<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fan_club_membership_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_club_id')->constrained('fan_clubs')->cascadeOnDelete();
            $table->unsignedSmallInteger('quarter'); // 1-4
            $table->unsignedSmallInteger('register_year');
            $table->unsignedInteger('active_member_count');
            $table->date('submitted_on');
            $table->boolean('audited')->default(false);
            $table->date('audited_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/verified

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['fan_club_id', 'register_year', 'quarter'], 'fcmr_fan_club_year_quarter_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fan_club_membership_registers');
    }
};
