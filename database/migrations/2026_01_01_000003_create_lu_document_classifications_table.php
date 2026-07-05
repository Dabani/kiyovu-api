<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Powers the classification dropdown on the polymorphic `documents` table (Art. 233). */
    public function up(): void
    {
        Schema::create('lu_document_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();  // public, internal, confidential, restricted
            $table->string('label_en');
            $table->string('label_fr');
            $table->string('label_rw');
            $table->unsignedSmallInteger('retention_years')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lu_document_classifications');
    }
};
