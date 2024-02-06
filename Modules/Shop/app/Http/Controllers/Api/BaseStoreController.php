<?php

namespace Modules\Shop\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Modules\Financial\app\Resources\OrderResource;
use Modules\Shop\app\Http\Requests\LanguageRequest;
use Modules\Shop\app\Http\Requests\StoreRequest;
use Modules\Shop\app\Models\Language;
use Modules\Shop\app\Models\Store;
use Modules\Shop\app\Resources\CategoryResource;
use Modules\Shop\app\Resources\StoreResource;
use function response;

class BaseStoreController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/store/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Store"
     *     },
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $stores = Store::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => StoreResource::collection($stores)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/store/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="slug",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="lang",
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
     *          "Store"
     *     },
     * )
     */
    public function create(StoreRequest $storeRequest): JsonResponse
    {
        // todo updated file
        $file = $storeRequest->file('thumbnail');
        $path = $file?->store('files');

        $store = Store::create([
            'user_id' => $storeRequest->user_id,
            'slug' => $storeRequest->slug,
            'thumbnail' => $path
        ]);
        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $store->id,
                'module_type' => Store::class,
                'lang' => $storeRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $storeRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $store->id,
                'module_type' => Store::class,
                'lang' => $storeRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $storeRequest->description
            ],
        ];
        Language::insert($languages);
        return response()->json([
            'status' => '200',
            'result' => ['data' => StoreResource::make($store)]
        ]);
    }


    /**
     * @OA\get(
     *     path="/api/store/v1/{store}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Store"
     *     },
     * )
     */
    public function show(Store $store)
    {
        return response()->json([
            'status' => '200',
            'result' => ['data' => StoreResource::make($store)]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/store/v1/{store}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
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
     *         name="slug",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="lang",
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
     *          "Store"
     *     },
     * )
     */
    public function update(StoreRequest $storeRequest, Store $store): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Store::class, $store]);

        $file = $storeRequest->file('file');
        $path = $file?->store('files');

        $store->update([
            'user_id' => $storeRequest->user_id,
            'slug' => $storeRequest->slug,
            'thumbnail' => $path ?? $store->thumbnail
        ]);
        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $store->id,
                'module_type' => Store::class,
                'lang' => $storeRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $storeRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $store->id,
                'module_type' => Store::class,
                'lang' => $storeRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $storeRequest->description
            ],
        ];

        foreach ($languages as $language) {
            Language::updateOrCreate(
                ['module_id' => $language['module_id'], 'key' => $language['key'], 'value' => $language['value']],
                $language
            );
        }
        return response()->json([
            'status' => '200',
            'result' => ['data' => StoreResource::make($store)]
        ]);
    }

//    /**
//     * @OA\delete(
//     *     path="/api/store/v1/{store}",
//     *     @OA\Response(response="200", description="Successful operation"),
//     *     @OA\Parameter(
//     *         name="store_id",
//     *         in="path",
//     *         @OA\Schema(
//     *             type="string",
//     *         ),
//     *     ),
//     *        tags={
//     *          "Store"
//     *     },
//     * )
//     */
//    public function destroy(Store $store): JsonResponse
//    {
//        $this->authorize(__FUNCTION__, [Store::class, $store]);
//        $store->delete();
//        return response()->json([
//            'status' => '200',
//            'result' => null
//        ]);
//    }

    /**
     * @OA\get(
     *     path="/api/store/v1/order{store}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="store_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Store"
     *     },
     * )
     */
    public function orders(Store $store): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Store::class, $store]);
        $orders = $store->orders;
        return response()->json([
            'status' => '200',
            'result' => ['data' => OrderResource::collection($orders)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/store/v1/language",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Store"
     *     },
     * )
     */
    public function getStoreByLanguage(LanguageRequest $languageRequest): JsonResponse
    {
        $lang = $languageRequest->lang ?? Config::get('languages.default');
        $stores = Store::with(['languages' => function ($query) use ($lang) {
            $query->where('lang', $lang);
        }])->get();
        return response()->json([
            'status' => '200',
            'result' => ['data' => StoreResource::collection($stores)]
        ]);
    }
}
