<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parental_consent_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->string('guardian_name');
            $table->string('relationship_to_minor');
            $table->string('guardian_phone');
            $table->date('consent_date');
            $table->text('activities_covered');
            $table->boolean('medical_treatment_consent')->default(false);
            $table->boolean('media_image_consent')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/signed/expired

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parental_consent_forms');
    }
};
