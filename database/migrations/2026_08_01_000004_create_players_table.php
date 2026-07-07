<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->string('position'); // free text (GK, CB, RW, etc.) — not a bounded organisational list
            $table->foreignId('team_id')->constrained('lu_player_teams');
            $table->string('national_id_or_passport');
            $table->string('ferwafa_registration_number')->nullable()->unique();
            $table->date('registration_date');
            $table->boolean('medical_clearance_certified')->default(false); // Art. 74 of the Constitution
            $table->date('medical_clearance_date')->nullable();
            $table->string('itc_reference')->nullable(); // International Transfer Certificate, for international transfers

            // Minors (Art. 200-201)
            $table->boolean('is_minor')->default(false);
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();

            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/active/inactive/transferred

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
