<?php

namespace Modules\Financial\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Financial\app\Models\Transaction;
use Modules\Financial\app\Resources\TransactionResource;
use Modules\Financial\Enumeration\TransactionStatusEnumeration;
use Modules\User\app\Models\User;

class BaseTransactionController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/transaction/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Transaction"
     *     },
     * )
     */
    public function index(): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Transaction::class]);
        $transactions = Transaction::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => TransactionResource::collection($transactions)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/transaction/v1/user",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Transaction"
     *     },
     * )
     */
    public function userTransactions(Request $request): JsonResponse
    {
        $request->validate([
            'status' => ['nullable', Rule::exists('enumerations', 'id')
                ->where(function (Builder $query) {
                    return $query->where('parent_id', TransactionStatusEnumeration::PARENT);
                })]
        ]);
        $transactions = Transaction::userIdFilter(Auth::id());
        if ($request->status) {
            $transactions->where('status', $request->status);
        }
        return response()->json([
            'status' => '200',
            'result' => ['data' => TransactionResource::collection($transactions->get())]
        ]);
    }

}
