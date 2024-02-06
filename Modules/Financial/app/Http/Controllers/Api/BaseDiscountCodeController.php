<?php

namespace Modules\Financial\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Financial\app\Http\Requests\DiscountCodeRequest;
use Modules\Financial\app\Models\DiscountCode;
use Modules\Financial\app\Resources\DiscountCodeResource;
use Modules\Financial\Services\DiscountCodeService;

class BaseDiscountCodeController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/discount/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Discount"
     *     },
     * )
     */
    public function index()
    {
        $this->authorize(__FUNCTION__, [DiscountCode::class]);
        $discounts = DiscountCode::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => DiscountCodeResource::collection($discounts)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/discount/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="discount",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="start_at",
     *         in="query",
     *         @OA\Schema(
     *             type="date",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="end_at",
     *         in="query",
     *         @OA\Schema(
     *             type="date",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="users",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *        tags={
     *          "Discount"
     *     },
     * )
     * @throws \Throwable
     */
    public function create(DiscountCodeRequest $discountCodeRequest): JsonResponse
    {
        $this->authorize(__FUNCTION__, [DiscountCode::class]);
        $discount = new DiscountCodeService($discountCodeRequest);
        $discount->create();

        return response()->json([
            'status' => '200',
            'result' => [
                'data' => null,
                'lang' => 'en'
            ]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/discount/v1/{discount_id}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="discount_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Discount"
     *     },
     * )
     */
    public function show(DiscountCode $discountCode): JsonResponse
    {
        $this->authorize(__FUNCTION__, [DiscountCode::class, $discountCode]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => DiscountCodeResource::make($discountCode)]
        ]);
    }

}
