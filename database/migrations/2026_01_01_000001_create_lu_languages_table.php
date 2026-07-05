<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dedicated lookup table — powers the language switcher dropdown.
     * Complies with rule: every dropdown = its own DB table, never enum/json.
     */
    public function up(): void
    {
        Schema::create('lu_languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();   // en, fr, rw
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_languages');
    }
};
