<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anti_doping_declarations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->date('declaration_date');
            $table->boolean('wada_list_acknowledged')->default(false);
            $table->boolean('tue_application_filed')->default(false);
            $table->text('tue_notes')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/signed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anti_doping_declarations');
    }
};
