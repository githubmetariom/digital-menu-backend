<?php

namespace Modules\Shop\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Modules\Shop\app\Http\Requests\FoodRequest;
use Modules\Shop\app\Http\Requests\LanguageRequest;
use Modules\Shop\app\Models\Food;
use Modules\Shop\app\Models\Language;
use Modules\Shop\app\Resources\CategoryResource;
use Modules\Shop\app\Resources\FoodResource;

class BaseFoodController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/food/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Food"
     *     },
     * )
     */
    public function index(): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Food::class]);
        $foods = Food::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => FoodResource::collection($foods)]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/food/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="category_id",
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
     *         name="price",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="dept",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
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
     *          "Food"
     *     },
     * )
     */
    public function create(FoodRequest $foodRequest)
    {
        // todo updated file
        $file = $foodRequest->file('thumbnail');
        $path = $file?->store('files');

        $food = Food::create([
            'slug' => $foodRequest->slug,
            'price' => $foodRequest->price,
            'category_id' => $foodRequest->category_id,
            'depot' => $foodRequest->depot,
            'status' => $foodRequest->status,
            'thumbnail' => $path
        ]);

        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $food->id,
                'module_type' => Food::class,
                'lang' => $foodRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $foodRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $food->id,
                'module_type' => Food::class,
                'lang' => $foodRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $foodRequest->description
            ],
        ];
        Language::insert($languages);
        return response()->json([
            'status' => '200',
            'result' => [FoodResource::make($food)]
        ]);
    }


    /**
     * @OA\get(
     *     path="/api/food/v1/{food}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="food_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Food"
     *     },
     * )
     */
    public function show(Food $food)
    {
        $this->authorize(__FUNCTION__, [Food::class]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => FoodResource::make($food)]
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/food/v1/{food}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="food_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
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
     *         name="price",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *      @OA\Parameter(
     *         name="dept",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
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
     *          "Food"
     *     },
     * )
     */
    public function update(FoodRequest $foodRequest, Food $food): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Food::class]);

        $file = $foodRequest->file('file');
        $path = $file?->store('files');

        $food->update([
            'slug' => $foodRequest->slug,
            'price' => $foodRequest->price,
            'category_id' => $foodRequest->category_id,
            'depot' => $foodRequest->depot,
            'status' => $foodRequest->status,
            'thumbnail' => $path ?? $food->thumbnail
        ]);

        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $food->id,
                'module_type' => Food::class,
                'lang' => $foodRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $foodRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $food->id,
                'module_type' => Food::class,
                'lang' => $foodRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $foodRequest->description
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
            'result' => ['data' => FoodResource::make($food)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/food/v1/language",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Food"
     *     },
     * )
     */
    public function getFoodByLanguage(LanguageRequest $languageRequest): JsonResponse
    {
        $lang = $languageRequest->lang ?? Config::get('languages.default');
        $foods = Food::with(['languages' => function ($query) use ($lang) {
            $query->where('lang', $lang);
        }])->get();
        return response()->json([
            'status' => '200',
            'result' => ['data' => FoodResource::collection($foods)]
        ]);
    }
//    /**
//     * @OA\delete(
//     *     path="/api/food/v1/{food}",
//     *     @OA\Response(response="200", description="Successful operation"),
//     *     @OA\Parameter(
//     *         name="food_id",
//     *         in="path",
//     *         @OA\Schema(
//     *             type="string",
//     *         ),
//     *     ),
//     *        tags={
//     *          "Food"
//     *     },
//     * )
//     */
//    public function destroy(Food $food)
//    {
//        $this->authorize(__FUNCTION__, [Food::class]);
//        $food->delete();
//        return response()->json([
//            'status' => '200',
//            'result' => null
//        ]);
//    }
}
