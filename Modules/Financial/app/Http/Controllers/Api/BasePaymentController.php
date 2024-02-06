<?php

namespace Modules\Financial\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Client\Curl\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Financial\app\Http\Requests\TransactionRequest;
use Modules\Financial\app\Models\Order;
use Modules\Financial\Enumeration\PaymentTypeEnumeration;
use Modules\Financial\Services\Payment\GatewayPaymentService;
use Modules\Financial\Services\Payment\WalletPaymentService;
use Throwable;

class BasePaymentController extends Controller
{

    /**
     * @OA\Post (
     *     path="/api/transaction/v1/payment/{order}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Payment"
     *     },
     * )
     */
    public function payment(TransactionRequest $transactionRequest): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Order::class, $transactionRequest->order_id]);
        try {

            DB::beginTransaction();
            if ($transactionRequest->payment_type == PaymentTypeEnumeration::WALLET) {
                $walletPayment = new WalletPaymentService();
                $transaction = $walletPayment->pay(Auth::id(), $transactionRequest->order_id);
            }

            if ($transactionRequest->payment_type == PaymentTypeEnumeration::GATEWAY) {
                $gatewayPayment = new GatewayPaymentService();
                $transaction = $gatewayPayment->pay(Auth::id(), $transactionRequest->order_id);

            }
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => [
                'data' => $transaction,
            ]
        ]);
    }

}
