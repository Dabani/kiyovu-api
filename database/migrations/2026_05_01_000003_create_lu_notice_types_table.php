<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_notice_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique(); // allegations, hearing
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->unsignedSmallInteger('minimum_notice_days')->default(14);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_notice_types');
    }
};
