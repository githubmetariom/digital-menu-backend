<?php

namespace Modules\Financial\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_INVOICE);
    }


    public function show(User $user, $order): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_ORDERS) || $order->user_id == $user->id;
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
