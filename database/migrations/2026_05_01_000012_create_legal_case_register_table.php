<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_case_register', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intake_id')->nullable()->constrained('legal_matter_intakes')->nullOnDelete();
            $table->string('case_reference')->unique();
            $table->foreignId('forum_id')->constrained('lu_legal_forums');
            $table->foreignId('classification_id')->nullable()->constrained('lu_document_classifications'); // confidentiality level
            $table->date('opened_on');
            $table->date('last_updated_on')->nullable();
            $table->text('outcome')->nullable();
            $table->date('closed_on')->nullable();
            $table->boolean('reported_to_executive_organ_quarterly')->default(false);
            $table->boolean('reported_to_ga_annually')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // open/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_case_register');
    }
};
