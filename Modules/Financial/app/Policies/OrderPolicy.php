<?php

namespace Modules\Financial\app\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Financial\app\Models\Order;
use Modules\User\app\Models\User;
use Modules\User\Enumeration\PermissionsEnum;

class OrderPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {

        return $this->isPermission($user, PermissionsEnum::ALL_ORDERS);
    }


    public function show(User $user, $order): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_ORDERS) || $order->user_id == $user->id;
    }


    public function update(User $user, $order): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_ORDERS) || $order->user_id == $user->id;
    }

    public function invoices(User $user, $order): bool
    {
        return $this->isPermission($user, PermissionsEnum::ALL_ORDERS) || $order->user_id == $user->id;
    }

    public function payment(User $user, $orderId): bool
    {
        $order = Order::find($orderId);
        return $order->user_id == $user->id;
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
