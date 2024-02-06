<?php

namespace App\Observers;

use Modules\User\app\Models\User;

class UserObserver
{

    public function created(User $user)
    {
        $user->walletRelation()->create();
    }
}
