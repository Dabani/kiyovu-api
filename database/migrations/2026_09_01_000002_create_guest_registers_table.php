<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_registers', function (Blueprint $table) {
            $table->id();
            $table->date('match_date');
            $table->string('event_description');
            $table->string('guest_name');
            $table->string('guest_organization')->nullable();
            $table->boolean('is_partner_guest')->default(false); // Marketing & Commercial Director hosts partner hospitality
            $table->string('host_name'); // Operations Director (official) or Marketing Director (partner)
            $table->date('ceo_approved_on')->nullable(); // required >= 48 hours before match
            $table->boolean('guest_signed')->default(false);
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/approved/attended

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_registers');
    }
};
