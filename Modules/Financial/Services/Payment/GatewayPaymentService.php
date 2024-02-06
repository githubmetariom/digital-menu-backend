<?php

namespace Modules\Financial\Services\Payment;

use Modules\Financial\app\Models\Invoice;
use Modules\Financial\app\Models\Order;
use Modules\Financial\app\Models\Transaction;
use Modules\Financial\Enumeration\GatewayEnumeration;
use Modules\Financial\Enumeration\PaymentTypeEnumeration;
use Modules\Financial\Enumeration\TransactionStatusEnumeration;
use Modules\Financial\Interfaces\PaymentServiceInterface;
use Modules\Financial\Services\Payment\Gateway\ZarinpalService;

class GatewayPaymentService implements PaymentServiceInterface
{
    private $gatewayService;

    const GATEWAYS = [
        GatewayEnumeration::ZARINPAL => ZarinpalService::class
    ];


    public function __construct($gatewayId = GatewayEnumeration::ZARINPAL)
    {
        $this->gatewayService = new (self::GATEWAYS[$gatewayId]);
    }

    public function pay($userId, $orderId,)
    {
        $order = Order::find($orderId);
        $amount = Invoice::orderIdFilter($orderId)->get()->sum('amount_total');
        $payUrl = $this->gatewayService->generatePayLink($amount);
        if ($payUrl) {
            Transaction::create([
                'user_id' => $userId,
                'order_id' => $order,
                'amount' => $amount,
                'type' => PaymentTypeEnumeration::GATEWAY,
                'status' => TransactionStatusEnumeration::PAID
            ]);
        }
        return $payUrl;
    }

//    public function paymentDone($referenceId)
//    {
//        $paymentIsVerified = $this->gatewayService->verify($referenceId);
//        if ($paymentIsVerified) {
//        }
//    }
}
