<?php

namespace Modules\Financial\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\Financial\app\Http\Requests\OrderRequest;
use Modules\Financial\app\Models\Order;
use Modules\Financial\app\Resources\InvoiceResource;
use Modules\Financial\app\Resources\OrderResource;
use Ramsey\Uuid\Uuid;

class BaseOrderController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/order/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Order"
     *     },
     * )
     */
    public function index(): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Order::class]);
        $categories = Order::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => OrderResource::collection($categories)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/order/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="stores",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="number",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *        tags={
     *          "Order"
     *     },
     * )
     */
    public function create(OrderRequest $orderRequest): JsonResponse
    {
        $order = Order::create($orderRequest->all());
        $orderStoreId = Str::uuid();
        foreach ($orderRequest->stores as $store) {
            $order->stores()->attach([$store => ['id' => $orderStoreId]]);
        }

        return response()->json([
            'status' => '200',
            'result' => ['data' => OrderResource::make($order)]
        ]);

    }


    /**
     * @OA\get(
     *     path="/api/order/v1/{order}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Order"
     *     },
     * )
     */
    public function show(Order $order)
    {
        $this->authorize(__FUNCTION__, [Order::class, $order]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => OrderResource::make($order)]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/order/v1/{order}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="stores",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="number",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *        tags={
     *          "Order"
     *     },
     * )
     */
    public function update(OrderRequest $orderRequest, Order $order): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Order::class, $order]);
        $order->update($orderRequest->all());
        foreach ($orderRequest->stores as $store) {
            $order->stores()->sync($store);
        }
        return response()->json([
            'status' => '200',
            'result' => ['data' => OrderResource::make($order)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/order/v1/invoice/{order}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="order_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Order"
     *     },
     * )
     */
    public function invoices(Order $order): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Order::class, $order]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => InvoiceResource::collection($order->invoicesRelation)]
        ]);
    }

}
