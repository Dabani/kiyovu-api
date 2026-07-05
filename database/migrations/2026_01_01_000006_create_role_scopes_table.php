<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Scopes a user's role to one specific owning entity, e.g.:
     *   - commission_president scoped to a single commissions.id
     *   - fan_club_rep scoped to a single fan_clubs.id
     *   - partner scoped to a single partners.id
     * A user with a role but NO row here is treated as globally scoped
     * (e.g. president, treasurer, super_admin).
     */
    public function up(): void
    {
        Schema::create('role_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role_name', 60); // matches spatie roles.name
            $table->string('scope_type', 60); // 'commission', 'fan_club', 'partner', 'player'...
            $table->unsignedBigInteger('scope_id'); // id in the relevant table (polymorphic-lite by type+id)
            $table->timestamps();

            $table->unique(['user_id', 'role_name', 'scope_type', 'scope_id'], 'role_scopes_unique');
            $table->index(['scope_type', 'scope_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_scopes');
    }
};
