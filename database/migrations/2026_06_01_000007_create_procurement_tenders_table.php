<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_tenders', function (Blueprint $table) {
            $table->id();
            $table->string('item_description');
            $table->unsignedBigInteger('estimated_value_rwf');
            $table->date('tender_published_on');
            $table->date('tender_closing_date');
            $table->text('evaluation_committee_names')->nullable();
            $table->string('awarded_vendor_name')->nullable();
            $table->date('award_date')->nullable();
            $table->unsignedBigInteger('awarded_value_rwf')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // published/under_evaluation/awarded/cancelled

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_tenders');
    }
};
