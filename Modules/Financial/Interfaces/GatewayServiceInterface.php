<?php

namespace Modules\Financial\Interfaces;

interface GatewayServiceInterface
{
    public function generatePayLink($amount);

    public function verify($amount, $authority);
}