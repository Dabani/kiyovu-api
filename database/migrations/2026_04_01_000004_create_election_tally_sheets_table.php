<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_tally_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nomination_id')->constrained('election_nominations')->cascadeOnDelete();
            $table->date('election_date');
            $table->unsignedInteger('votes_received');
            $table->unsignedInteger('invalid_ballots_count')->default(0); // recorded once per position, duplicated per row for simplicity
            $table->boolean('independent_observer_present')->default(false);
            $table->text('observer_names')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_tally_sheets');
    }
};
