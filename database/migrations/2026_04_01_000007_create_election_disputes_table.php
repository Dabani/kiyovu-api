<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('lu_elected_positions');
            $table->unsignedSmallInteger('election_cycle_year');
            $table->foreignId('dispute_ground_id')->constrained('lu_dispute_grounds');
            $table->string('submitted_by_name');
            $table->date('submitted_on');
            $table->text('grounds_detail');
            $table->boolean('referred_to_cro')->default(false); // Conflict Resolution Organ
            $table->text('determination')->nullable();
            $table->date('determination_date')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(upheld)/rejected(dismissed)

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_disputes');
    }
};
