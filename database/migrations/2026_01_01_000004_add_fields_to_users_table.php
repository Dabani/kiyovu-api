<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Extends Laravel's default users table.
     * NOTE: run `php artisan migrate` after the framework's own
     * 0001_01_01_000000_create_users_table has already run.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('national_id')->nullable()->unique()->after('phone');
            $table->date('date_of_birth')->nullable()->after('national_id');

            // FK to lu_languages — user's preferred UI language. No enum, DB-driven.
            $table->foreignId('preferred_language_id')
                ->nullable()
                ->after('date_of_birth')
                ->constrained('lu_languages')
                ->nullOnDelete();

            // FK to lu_statuses — generic status lifecycle (Active/Suspended/etc.)
            $table->foreignId('status_id')
                ->nullable()
                ->after('preferred_language_id')
                ->constrained('lu_statuses')
                ->nullOnDelete();

            $table->string('avatar_path')->nullable()->after('status_id');
            $table->timestamp('last_login_at')->nullable()->after('avatar_path');

            $table->foreignId('created_by')->nullable()->after('last_login_at')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')
                ->constrained('users')->nullOnDelete();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('preferred_language_id');
            $table->dropConstrainedForeignId('status_id');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn([
                'first_name', 'last_name', 'phone', 'national_id', 'date_of_birth',
                'avatar_path', 'last_login_at', 'deleted_at',
            ]);
        });
    }
};
