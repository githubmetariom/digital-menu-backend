<?php

namespace Modules\Financial\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Financial\app\Http\Requests\InvoiceRequest;
use Modules\Financial\app\Models\DiscountCode;
use Modules\Financial\app\Models\Invoice;
use Modules\Financial\app\Resources\InvoiceResource;
use Modules\Financial\app\Resources\OrderResource;
use Modules\Financial\Enumeration\TaxEnum;
use Modules\General\app\Models\Enumeration;
use Modules\Shop\app\Http\Controllers\Api\V1\FoodController;
use Modules\Shop\app\Models\Food;
use Psy\Util\Str;
use Throwable;

class BaseInvoiceController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/invoice/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Invoice"
     *     },
     * )
     */
    public function index(): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Invoice::class]);
        $categories = Invoice::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => InvoiceResource::collection($categories)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/invoic/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="foods",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="discount_code",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Invoice"
     *     },
     * )
     */
    public function create(InvoiceRequest $invoiceRequest): JsonResponse
    {
        try {
            DB::beginTransaction();
            $amount = Food::findMany($invoiceRequest->foods)->sum('price');
            $discountCode = DiscountCode::codeFilter($invoiceRequest->discount_code)->first();

            if ($discountCode->type == 'fixed') {
                $discountCodeAmount = $discountCode->discount;
                $total = $amount - $discountCode->discount;

            } elseif ($discountCode->type == 'percentage') {
                $discountCodeAmount = ($amount * ($discountCode->discount / 100));
                $total = $amount - ($amount * ($discountCode->discount / 100));
            }
            $tax = Enumeration::find(TaxEnum::TAX_RATE);
            $tax = $total * (intval($tax->title) / 100);
            $finalAmount = $total + $tax;
            $invoice = Invoice::create([
                'order_id' => $invoiceRequest->order_id,
                'amount' => $amount,
                'discount' => $discountCodeAmount,
                'total' => $total,
                'amount_total' => $finalAmount
            ]);

            foreach ($invoiceRequest->foods as $foodId) {
                $invoice->foodsRelation()->attach([$foodId => ['id' => \Illuminate\Support\Str::uuid()]]);
            }
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => ['data' => InvoiceResource::make($invoice)]
        ]);
    }


    /**
     * @OA\get(
     *     path="/api/invoice/v1/{invoice}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="invoice_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Invoice"
     *     },
     * )
     */
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'status' => '200',
            'result' => ['data' => InvoiceResource::make($invoice)]
        ]);
    }

}
