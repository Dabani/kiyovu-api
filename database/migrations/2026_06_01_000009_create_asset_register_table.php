<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_register', function (Blueprint $table) {
            $table->id();
            $table->string('asset_tag')->unique();
            $table->string('description');
            $table->foreignId('category_id')->constrained('lu_asset_categories');
            $table->date('acquisition_date');
            $table->unsignedBigInteger('acquisition_cost_rwf');
            $table->string('custodian_name');
            $table->string('location')->nullable();
            $table->date('last_verified_on')->nullable(); // annual physical verification, Art. 901
            $table->boolean('disposal_approved')->default(false);
            $table->date('disposed_on')->nullable();
            $table->unsignedBigInteger('disposal_proceeds_rwf')->nullable();
            $table->foreignId('status_id')->constrained('lu_statuses'); // active/disposed/lost

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_register');
    }
};
