<?php

namespace Modules\Shop\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Shop\app\Models\Store;
use Modules\User\app\Models\Address;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class StorePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Store $store): bool
    {
        return $user->id == $store->user_id;
    }

    public function orders(User $user, Store $store): bool
    {
        return $user->id == $store->user_id || $this->isPermission($user, PermissionsEnum::ALL_STORES);
    }

    public function destroy(User $user, Store $store): bool
    {
        return $user->id == $store->user_id;
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
