<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_expenditure_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique(); // routine, significant, major, capital
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->unsignedBigInteger('min_amount_rwf');
            $table->unsignedBigInteger('max_amount_rwf')->nullable(); // null = no upper bound
            $table->string('required_authoriser_en'); // "CEO", "CEO and Treasurer (co-signed)", etc.
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_expenditure_tiers');
    }
};
