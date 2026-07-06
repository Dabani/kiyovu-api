<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_matter_intakes', function (Blueprint $table) {
            $table->id();
            $table->text('matter_description');
            $table->string('notified_by_name');
            $table->string('notified_by_role')->nullable(); // organ/commission/HQ personnel/player
            $table->date('notified_on'); // within 48 hours of becoming aware, Art. 613
            $table->foreignId('forum_id')->nullable()->constrained('lu_legal_forums');
            $table->foreignId('urgency_id')->nullable()->constrained('lu_legal_urgency');
            $table->date('classified_on')->nullable(); // within 5 days of intake
            $table->date('deadline_date')->nullable();
            $table->boolean('reported_to_president')->default(false); // mandatory if deadline < 14 days
            $table->foreignId('status_id')->constrained('lu_statuses'); // pending/classified/escalated/closed

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_matter_intakes');
    }
};
