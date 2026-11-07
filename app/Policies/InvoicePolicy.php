<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class InvoicePolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('finance.view') || $user->is_platform_user;
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $this->allow($user, 'finance.view', $invoice);
    }

    public function create(User $user): bool
    {
        return $user->can('finance.manage') || $user->is_platform_user;
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $this->allow($user, 'finance.manage', $invoice);
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $this->allow($user, 'finance.manage', $invoice);
    }
}
