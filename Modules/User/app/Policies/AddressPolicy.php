<?php

namespace Modules\User\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\app\Models\Address;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class AddressPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::NOTIFY);
    }

    public function show(User $user, Address $address): bool
    {
        return $user->id == $address->user_id || $this->isPermission($user, PermissionsEnum::ALL_ADDRESS);
    }

    public function update(User $user, Address $address): bool
    {
        return $user->id == $address->user_id;
    }

    public function destroy(User $user, Address $address): bool
    {
        return $user->id == $address->user_id;
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
