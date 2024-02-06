<?php

namespace Modules\User\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\User\app\Http\Requests\SignUpRequest;
use Modules\User\app\Http\Requests\UserRequest;
use Modules\User\app\Models\OtpRequest;
use Modules\User\app\Models\Role;
use Modules\User\app\Models\User;
use Modules\User\app\Resources\UserResource;
use Modules\User\app\Rules\OtpVerifyRule;
use Modules\User\Enumeration\RolesEnum;
use Throwable;
use function view;

class BaseUserController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/users/v1/otp-request",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function otpCodeRequest(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => 'required|numeric|digits:11'
        ]);
        $code = rand(1000, 9999);

        //todo Activate SMS panel
        OtpRequest::create([
            'mobile' => $request->mobile,
            'ip' => $request->ip(),
            'code' => 1111
        ]);

        return response()->json([
            'status' => '200',
            'result' => null
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/v1/signup",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="referral_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="family",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="national_code",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="date_of_birth",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="roles",
     *         in="query",
     *         @OA\Schema(
     *             type="array",
     *           @OA\Items(
     *                 @OA\Property(property="id", type="string")
     *             )
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="thumbnail",
     *         in="query",
     *         @OA\Schema(
     *             type="file",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function signup(SignUpRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $otpRequest = OtpRequest::verifiedCode($request->mobile, $request->code)
                ->first();
            $otpRequest->update(['is_verify' => true]);

            $file = $request->file('thumbnail');
            $path = $file?->store('files');

            $referralId = null;
            if ($request->has('referral_code')) {
                $referringUser = User::referralCodeFilter($request->referral_code)->first();
                $referralId = $referringUser?->id;
            }

            $user = User::create([
                'referral_id' => $referralId,
                'referral_code' => Str::random(10),
                'name' => $request->name,
                'family' => $request->family,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'national_code' => $request->national_code,
                'date_of_birth' => $request->date_of_birth,
                'thumbnail' => $path,
            ]);

            $roles = Role::nameFilter($request->roles)->get();
            foreach ($roles as $role) {
                $user->rolesRelation()->attach([$role->id => ['id' => \Illuminate\Support\Str::uuid()]]);
                foreach ($role->permissionsRelation as $value) {
                    $user->permissionsRelation()->attach([$value->id => ['id' => \Illuminate\Support\Str::uuid()]]);
                }
            }
            $token = $user->createToken('apiToken')->plainTextToken;
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => ['token' => $token, 'data' => UserResource::make($user)]
        ]);

    }

    /**
     * @OA\Post(
     *     path="/api/users/v1/login",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="code",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'mobile' => ['required', 'numeric', 'digits:11', 'exists:users,mobile'],
            'code' => ['required', 'numeric', new OtpVerifyRule(request()->mobile)]
        ]);

        try {
            DB::beginTransaction();

            $otpRequest = OtpRequest::verifiedCode($request->mobile, $request->code)
                ->first();
            $otpRequest->update(['is_verify' => true]);
            $user = User::mobileFilter($request->mobile)->first();
            $token = $user->createToken('apiToken')->plainTextToken;

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => ['token' => $token, 'data' => UserResource::make($user)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/v1/{user_id}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="referral_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="family",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="national_code",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="date_of_birth",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="thumbnail",
     *         in="query",
     *         @OA\Schema(
     *             type="file",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function userUpdate(UserRequest $userRequest, User $user): JsonResponse
    {
        $this->authorize(__FUNCTION__, [User::class, $user->id]);
        try {
            DB::beginTransaction();
            $file = $userRequest->file('file');
            $path = $file?->store('files');

            $user->update([
                'referral_id' => $userRequest->referral_id,
                'name' => $userRequest->name,
                'family' => $userRequest->family,
                'mobile' => $userRequest->mobile,
                'email' => $userRequest->email,
                'national_code' => $userRequest->national_code,
                'date_of_birth' => $userRequest->date_of_birth,
                'thumbnail' => $path,
            ]);

            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => ['data' => UserResource::make($user)]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/users/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="referral_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="family",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="national_code",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="date_of_birth",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="thumbnail",
     *         in="query",
     *         @OA\Schema(
     *             type="file",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     * @throws AuthorizationException|Throwable
     */
    public function userCreate(UserRequest $userRequest): JsonResponse
    {
        $userRequest->merge(['role' => $userRequest
        ]);
        $userRequest->validate(['role' => [
            'in:'
            . RolesEnum::SUPERUSER . ',' .
            RolesEnum::BUYER . ',' .
            RolesEnum::SALES_REPRESENTATIVE . ',' .
            RolesEnum::SELLER
        ]]);

        $this->authorize(__FUNCTION__, [User::class]);
        try {
            DB::beginTransaction();
            $file = $userRequest->file('file');
            $path = $file?->store('files');

            $user = User::create([
                'referral_id' => $userRequest->referral_id,
                'name' => $userRequest->name,
                'family' => $userRequest->family,
                'mobile' => $userRequest->mobile,
                'email' => $userRequest->email,
                'national_code' => $userRequest->national_code,
                'date_of_birth' => $userRequest->date_of_birth,
                'thumbnail' => $path,
            ]);

            $role = Role::where('name', $userRequest->role)->first();
            $user->rolesRelation()->attach($role);
            $user->permissionsRelation()->attach($role->permissionsRelation);
            DB::commit();
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json([
            'status' => '200',
            'result' => ['date' => UserResource::make($user)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/v1/balance",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function getBalance(): JsonResponse
    {
        $user = User::find(Auth::id());
        return response()->json([
            'status' => '200',
            'result' => ['balance' => $user->walletRelation->amount]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/v1/referral/{user_id}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="number",
     *         ),
     *     ),
     *        tags={
     *          "User"
     *     },
     * )
     */
    public function referrals(User $user): JsonResponse
    {
        $this->authorize(__FUNCTION__, [User::class, $user->id]);
        return response()->json([
            'status' => '200',
            'result' => ['date' => UserResource::collection($user->referrals)]
        ]);
    }

}
