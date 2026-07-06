<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_hq_positions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->string('division', 20); // sport, business, executive
            $table->boolean('involves_minors')->default(false); // triggers HR-002 mandatory check
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_hq_positions');
    }
};
