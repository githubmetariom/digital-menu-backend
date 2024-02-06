<?php

namespace Modules\Shop\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class FoodPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_FOODS);
    }

    public function show(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_FOODS);
    }

    public function update(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_FOODS);
    }

    public function destroy(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_FOODS);
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
