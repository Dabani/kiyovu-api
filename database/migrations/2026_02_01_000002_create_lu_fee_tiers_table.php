<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_fee_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();   // tier_1 .. tier_6
            $table->string('label_en');              // "Tier 1: INZIRA (The Pathway)"
            $table->string('label_fr');
            $table->string('label_rw');
            $table->unsignedInteger('min_monthly_rwf');
            $table->unsignedInteger('max_monthly_rwf');
            $table->text('amenities_en')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_fee_tiers');
    }
};
