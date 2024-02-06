<?php

namespace Modules\User\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\User\app\Emails\Email;
use Modules\User\app\Http\Requests\NotifyRequest;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\UserResource;
use Modules\User\Enumeration\NotifyTypeEnum;
use Modules\User\Enumeration\PermissionsEnum;
use Modules\User\Services\SmsService;
use Throwable;

class NotificationController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/notify",
     *     @OA\Response(response="200", description="Successful operation"),
        *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="body",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="uuids",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function notify(NotifyRequest $notifyRequest): JsonResponse
    {
        $this->authorize(__FUNCTION__, [User::class]);

        try {
            DB::beginTransaction();
            $users = User::whereIn('id', $notifyRequest->uuids)->get();

            if ($notifyRequest->type == NotifyTypeEnum::EMAIL) {
                foreach ($users as $user) {
                    Mail::to($user->email)->send(new Email($notifyRequest->title, $notifyRequest->body, $user->email));
                }
            } else {
                foreach ($users as $user) {
                    $smsService = new SmsService($notifyRequest->title, $notifyRequest->body, $user->mobile);
                    $smsService->run();
                }
            }
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => null
        ]);
    }
}
