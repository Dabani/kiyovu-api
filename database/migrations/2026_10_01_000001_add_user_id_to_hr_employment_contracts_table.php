<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Lets a staff/HQ personnel record be linked back to a login account, same pattern as members.user_id. */
    public function up(): void
    {
        Schema::table('hr_employment_contracts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('candidate_id')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('hr_employment_contracts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
