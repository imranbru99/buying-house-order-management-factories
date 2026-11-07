<?php

namespace App\Policies\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ChecksTenantAccess
{
    protected function sameTenant(User $user, Model $model): bool
    {
        if ($user->is_platform_user) {
            return true;
        }

        if (! isset($model->tenant_id)) {
            return false;
        }

        return (int) $user->current_tenant_id === (int) $model->tenant_id;
    }

    protected function allow(User $user, string $permission, ?Model $model = null): bool
    {
        if ($model !== null && ! $this->sameTenant($user, $model)) {
            return false;
        }

        if ($user->is_platform_user) {
            return true;
        }

        return $user->can($permission);
    }
}
