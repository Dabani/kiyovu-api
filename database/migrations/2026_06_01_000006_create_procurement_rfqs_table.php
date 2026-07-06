<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('item_description');
            $table->unsignedBigInteger('estimated_value_rwf');
            $table->unsignedTinyInteger('quotations_received')->default(0); // min. 3 required
            $table->text('evaluation_notes')->nullable();
            $table->string('selected_vendor_name')->nullable();
            $table->date('rfq_date');
            $table->date('award_date')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // draft/quotations_open/evaluated/awarded

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_rfqs');
    }
};
