<?php

namespace Modules\Financial\Services\Payment;

use Modules\Financial\app\Models\Invoice;
use Modules\Financial\app\Models\Order;
use Modules\Financial\app\Models\Transaction;
use Modules\Financial\Enumeration\TransactionStatusEnumeration;
use Psy\Util\Str;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Financial\Enumeration\PaymentTypeEnumeration;
use Modules\Financial\Interfaces\PaymentServiceInterface;

class WalletPaymentService implements PaymentServiceInterface
{

    /**
     * @throws Throwable
     */
    public function pay($userId, $orderId)
    {
        $amount = Invoice::orderIdFilter($orderId)->get()->sum('amount_total');
        return Transaction::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'user_id' => $userId,
            'order_id' => $orderId,
            'amount' => $amount,
            'type' => PaymentTypeEnumeration::WALLET,
            'status' => TransactionStatusEnumeration::PAID
        ]);
    }


}
