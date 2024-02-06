<?php

namespace Modules\Shop\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Shop\app\Models\Category;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class CategoryPolicy
{
    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_CATEGORIES);
    }

    public function show(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_CATEGORIES);
    }

    public function update(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_CATEGORIES);
    }

    public function destroy(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_CATEGORIES);
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
