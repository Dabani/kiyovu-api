<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_handover_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('lu_elected_positions');
            $table->string('outgoing_official_name');
            $table->string('incoming_official_name');
            $table->date('handover_date'); // must be within 30 days of certification, Art. 71
            $table->text('outstanding_matters')->nullable();
            $table->text('key_contacts')->nullable();
            $table->text('pending_decisions')->nullable();
            $table->boolean('access_and_assets_transferred')->default(false);
            $table->boolean('outgoing_signed')->default(false);
            $table->boolean('incoming_signed')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_handover_reports');
    }
};
