<?php

namespace Modules\User\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class UserPolicy
{
    use HandlesAuthorization;


    public function userUpdate(User $user, $userId): bool
    {
        return $this->isPermission($user, PermissionsEnum::USER_UPDATE) || $user->id == $userId;
    }

    public function referrals(User $user, $userId): bool
    {
        return $this->isPermission($user, PermissionsEnum::USER_UPDATE) || $user->id == $userId;
    }

    public function userCreate(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::USER_CREATE);
    }

    public function notify(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::NOTIFY);
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
