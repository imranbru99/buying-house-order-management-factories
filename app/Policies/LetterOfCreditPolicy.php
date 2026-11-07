<?php

namespace App\Policies;

use App\Models\LetterOfCredit;
use App\Models\User;
use App\Policies\Concerns\ChecksTenantAccess;

class LetterOfCreditPolicy
{
    use ChecksTenantAccess;

    public function viewAny(User $user): bool
    {
        return $user->can('finance.view') || $user->is_platform_user;
    }

    public function view(User $user, LetterOfCredit $lc): bool
    {
        return $this->allow($user, 'finance.view', $lc);
    }

    public function create(User $user): bool
    {
        return $user->can('finance.manage') || $user->is_platform_user;
    }

    public function update(User $user, LetterOfCredit $lc): bool
    {
        return $this->allow($user, 'finance.manage', $lc);
    }

    public function delete(User $user, LetterOfCredit $lc): bool
    {
        return $this->allow($user, 'finance.manage', $lc);
    }
}
