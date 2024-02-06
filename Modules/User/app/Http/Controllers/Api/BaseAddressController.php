<?php

namespace Modules\User\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\User\app\Http\Requests\AddressRequest;
use Modules\User\app\Models\Address;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\AddressResource;

class BaseAddressController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/address/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Address"
     *     },
     * )
     */
    public function index(): JsonResponse
    {
        $this->authorize(__FUNCTION__, [User::class]);
        $address = Address::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => AddressResource::collection($address)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/address/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         @OA\Schema(
     *             type="inreger",
     *         ),
     *     ),
     *        tags={
     *          "Address"
     *     },
     * )
     */
    public function create(AddressRequest $addressRequest): JsonResponse
    {
        $address = Address::create($addressRequest->all());
        return response()->json([
            'status' => '200',
            'result' => ['data' => AddressResource::make($address)]
        ]);

    }

    /**
     * @OA\get(
     *     path="/api/address/v1/{address}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="address_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Address"
     *     },
     * )
     */
    public function show(Request $request, Address $address)
    {
        $this->authorize(__FUNCTION__, [Address::class, $address]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => AddressResource::make($address)]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/address/v1/{address}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="address_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="location",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         @OA\Schema(
     *             type="inreger",
     *         ),
     *     ),
     *        tags={
     *          "Address"
     *     },
     * )
     */
    public function update(AddressRequest $addressRequest, Address $address): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Address::class, $address]);
        $address->update($addressRequest->all());
        return response()->json([
            'status' => '200',
            'result' => ['data' => AddressResource::make($address)]
        ]);
    }

    /**
     * @OA\delete(
     *     path="/api/address/v1/{address}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="address_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Address"
     *     },
     * )
     */
    public function destroy(Address $address)
    {
        $this->authorize(__FUNCTION__, [Address::class, $address]);
        $address->delete();
        return response()->json([
            'status' => '200',
            'result' => null
        ]);
    }
}
