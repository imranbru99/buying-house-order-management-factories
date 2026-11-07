<?php

namespace App\Models;

use App\Support\Tenancy\TenantContext;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'current_tenant_id',
        'phone',
        'locale',
        'timezone',
        'is_platform_user',
        'is_demo',
        'portal_type',
        'external_organization_type',
        'external_organization_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_platform_user' => 'boolean',
            'is_demo' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function switchToTenant(?Tenant $tenant): void
    {
        $this->forceFill(['current_tenant_id' => $tenant?->id])->save();

        if ($tenant !== null) {
            app(TenantContext::class)->set($tenant);
        } else {
            app(TenantContext::class)->forget();
        }
    }

    public function belongsToCurrentTenant(): bool
    {
        if ($this->is_platform_user) {
            return true;
        }

        $tenantId = app(TenantContext::class)->id();

        return $tenantId !== null && (int) $this->current_tenant_id === $tenantId;
    }

    public function isPlatformUser(): bool
    {
        return (bool) $this->is_platform_user;
    }

    public function isDemoUser(): bool
    {
        return (bool) $this->is_demo;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->is_platform_user) {
            return in_array($panel->getId(), ['platform', 'app'], true);
        }

        $portal = $this->portal_type ?? 'tenant';

        return match ($panel->getId()) {
            'platform' => false,
            'app' => $portal === 'tenant',
            'buyer' => $portal === 'buyer',
            'factory' => $portal === 'factory',
            'supplier' => $portal === 'supplier',
            default => false,
        };
    }
}
