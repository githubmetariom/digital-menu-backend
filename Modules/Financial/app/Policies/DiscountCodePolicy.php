<?php

namespace Modules\Financial\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class DiscountCodePolicy
{
    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::DISCOUNT_CODE);
    }

    public function create(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::DISCOUNT_CODE);
    }

    public function show(User $user, $discount): bool
    {
        return $this->isPermission($user, PermissionsEnum::DISCOUNT_CODE) || $discount->user_id == $user->id;
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
