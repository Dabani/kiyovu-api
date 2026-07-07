<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->date('term_start');
            $table->date('term_end');
            $table->unsignedBigInteger('base_salary_rwf');
            $table->text('bonuses_notes')->nullable();
            $table->text('benefits_notes')->nullable();
            $table->text('player_obligations');
            $table->text('organisation_obligations');
            $table->text('termination_grounds')->nullable();
            $table->text('dispute_resolution_mechanism')->nullable();
            $table->date('ceo_signed_on')->nullable();
            $table->date('sporting_director_signed_on')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/pending/signed/terminated

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_contracts');
    }
};
