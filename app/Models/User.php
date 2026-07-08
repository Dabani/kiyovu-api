<?php

namespace App\Models;

use App\Models\Lookups\LuLanguage;
use App\Models\Lookups\LuStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'national_id',
        'date_of_birth', 'password', 'preferred_language_id', 'status_id',
        'avatar_path',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function preferredLanguage(): BelongsTo
    {
        return $this->belongsTo(LuLanguage::class, 'preferred_language_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(LuStatus::class, 'status_id');
    }

    public function roleScopes(): HasMany
    {
        return $this->hasMany(RoleScope::class);
    }

    /**
     * True if the user's given role is scoped to a specific entity
     * (e.g. commission_president -> one commission) rather than global.
     */
    public function isScopedTo(string $roleName, string $scopeType, int $scopeId): bool
    {
        return $this->roleScopes()
            ->where('role_name', $roleName)
            ->where('scope_type', $scopeType)
            ->where('scope_id', $scopeId)
            ->exists();
    }

    /** Sends our SPA-aware reset notification instead of Laravel's default Blade-route version. */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
