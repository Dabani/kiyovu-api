<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_elected_positions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->unsignedTinyInteger('term_years')->default(3);
            $table->boolean('requires_criminal_record_certificate')->default(false); // Art. 68 — President/VP
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_elected_positions');
    }
};
