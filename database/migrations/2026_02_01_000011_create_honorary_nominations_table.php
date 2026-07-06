<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honorary_nominations', function (Blueprint $table) {
            $table->id();
            $table->string('nominee_name');
            $table->foreignId('nominee_type_id')->constrained('lu_nominee_types');
            $table->text('basis_for_nomination'); // sports development contribution, service, values, role model
            $table->boolean('executive_organ_endorsed')->default(false);
            $table->boolean('board_endorsed')->default(false);
            $table->date('nominated_on');
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved(GA)/rejected
            $table->date('ga_decision_date')->nullable();

            // Consultative-only constraints acknowledgement (Art. 22)
            $table->boolean('conflict_of_interest_disclosed')->default(false);
            $table->text('conflict_of_interest_notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honorary_nominations');
    }
};
