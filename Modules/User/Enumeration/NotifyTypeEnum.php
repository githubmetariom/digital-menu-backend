<?php

namespace Modules\User\Enumeration;

use Illuminate\Validation\Rules\Enum;

class NotifyTypeEnum extends Enum
{
    const SMS = 'sms';
    const EMAIL = 'email';
}
