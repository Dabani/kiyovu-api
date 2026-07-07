<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_loan_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->foreignId('direction_id')->constrained('lu_loan_directions');
            $table->string('counterparty_club_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('compensation_rwf')->nullable();
            $table->text('obligations_notes')->nullable();
            $table->text('recall_provisions')->nullable();
            $table->boolean('executive_organ_approved')->default(false); // Art. 197 — domestic transfers/loans
            $table->boolean('board_notified')->default(false); // Art. 197 — international transfers
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/active/completed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_loan_agreements');
    }
};
