<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lu_incident_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique(); // pitch_invasion, violence, prohibited_substances, discriminatory_behaviour, property_destruction, other
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
        Schema::dropIfExists('lu_incident_types');
    }
};
