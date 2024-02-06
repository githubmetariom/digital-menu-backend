<?php

namespace Modules\Financial\Interfaces;

interface PaymentServiceInterface
{
    public function pay($userId, $order_id);
}
