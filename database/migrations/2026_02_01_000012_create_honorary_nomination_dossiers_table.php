<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honorary_nomination_dossiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('honorary_nomination_id')->constrained('honorary_nominations')->cascadeOnDelete();
            $table->text('contributions_summary');
            $table->text('justification');
            $table->date('prepared_on');
            $table->string('prepared_by_name'); // dossier compiler, may not be a system user
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honorary_nomination_dossiers');
    }
};
