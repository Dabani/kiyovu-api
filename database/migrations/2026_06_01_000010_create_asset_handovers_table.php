<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('asset_register')->cascadeOnDelete();
            $table->string('outgoing_custodian_name');
            $table->string('incoming_custodian_name');
            $table->date('handover_date');
            $table->text('condition_notes')->nullable();
            $table->boolean('outgoing_signed')->default(false);
            $table->boolean('incoming_signed')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/completed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_handovers');
    }
};
