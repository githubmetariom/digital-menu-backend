<?php

namespace Modules\Financial\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class TransactionPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_TRANSACTIONS);
    }

    private function isPermission(User $user, $permission): bool
    {
        $userPermission = [];
        foreach ($user->permissionsRelation as $item) {
            $userPermission[] = $item->name;
        }
        return in_array($permission, $userPermission);
    }
}
