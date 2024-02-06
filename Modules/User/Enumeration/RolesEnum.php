<?php

namespace Modules\User\Enumeration;

use Illuminate\Validation\Rules\Enum;

class RolesEnum extends Enum
{
    const SUPERUSER = 'superuser';
    const SALES_REPRESENTATIVE = 'sales representative';
    const SELLER = 'seller';
    const BUYER = 'buyer';
}
