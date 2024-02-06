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
use Modules\Shop\app\Http\Requests\CategoryRequest;
use Modules\Shop\app\Http\Requests\LanguageRequest;
use Modules\Shop\app\Models\Category;
use Modules\Shop\app\Models\Food;
use Modules\Shop\app\Models\Language;
use Modules\Shop\app\Models\Store;
use Modules\Shop\app\Resources\CategoryResource;
use Modules\Shop\app\Resources\StoreResource;

class BaseCategoryController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\get(
     *     path="/api/category/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *        tags={
     *          "Category"
     *     },
     * )
     */
    public function index()
    {
        $this->authorize(__FUNCTION__, [Category::class]);
        $categories = Category::all();
        return response()->json([
            'status' => '200',
            'result' => ['data' => CategoryResource::collection($categories)]
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/category/v1/",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="store_id",
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
     *         name="thumbnail",
     *         in="query",
     *         @OA\Schema(
     *             type="file",
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
     *        tags={
     *          "Category"
     *     },
     * )
     */
    public function create(CategoryRequest $categoryRequest): JsonResponse
    {
        // todo updated file
        $file = $categoryRequest->file('thumbnail');
        $path = $file?->store('files');

        $category = Category::create([
            'store_id' => $categoryRequest->store_id,
            'slug' => $categoryRequest->slug,
            'thumbnail' => $path
        ]);

        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $category->id,
                'module_type' => Category::class,
                'lang' => $categoryRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $categoryRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $category->id,
                'module_type' => Category::class,
                'lang' => $categoryRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $categoryRequest->description
            ],
        ];
        Language::insert($languages);
        return response()->json([
            'status' => '200',
            'result' => ['data' => CategoryResource::make($category)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/category/v1/{category}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Category"
     *     },
     * )
     */
    public function show(Category $category): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Category::class]);
        return response()->json([
            'status' => '200',
            'result' => ['data' => CategoryResource::make($category)]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/category/v1/{category}",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="path",
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
     *          "Category"
     *     },
     * )
     */
    public function update(CategoryRequest $categoryRequest, Category $category): JsonResponse
    {
        $this->authorize(__FUNCTION__, [Category::class]);

        $file = $categoryRequest->file('file');
        $path = $file?->store('files');

        $category->update([
            'store_id' => $categoryRequest->store_id,
            'slug' => $categoryRequest->slug,
            'thumbnail' => $path ?? $category->thumbnail
        ]);
        $languages = [
            [
                'id' => Str::uuid(),
                'module_id' => $category->id,
                'module_type' => Category::class,
                'lang' => $categoryRequest->lang ?? Config::get('languages.default'),
                'key' => 'title',
                'value' => $categoryRequest->title
            ],
            [
                'id' => Str::uuid(),
                'module_id' => $category->id,
                'module_type' => Category::class,
                'lang' => $categoryRequest->lang ?? Config::get('languages.default'),
                'key' => 'description',
                'value' => $categoryRequest->description
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
            'result' => ['data' => CategoryResource::make($category)]
        ]);
    }

    /**
     * @OA\get(
     *     path="/api/category/v1/language",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="lang",
     *         in="path",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Category"
     *     },
     * )
     */
    public function getCategoryByLanguage(LanguageRequest $languageRequest): JsonResponse
    {
        $lang = $languageRequest->lang ?? Config::get('languages.default');
        $categories = Category::with(['languages' => function ($query) use ($lang) {
            $query->where('lang', $lang);
        }])->get();
        return response()->json([
            'status' => '200',
            'result' => ['data' => CategoryResource::collection($categories)]
        ]);
    }
//    /**
//     * @OA\delete(
//     *     path="/api/category/v1/{category}",
//     *     @OA\Response(response="200", description="Successful operation"),
//     *     @OA\Parameter(
//     *         name="category_id",
//     *         in="path",
//     *         @OA\Schema(
//     *             type="string",
//     *         ),
//     *     ),
//     *        tags={
//     *          "Category"
//     *     },
//     * )
//     */
//    public function destroy(Category $category): JsonResponse
//    {
//        $this->authorize(__FUNCTION__, [Category::class]);
//        $category->delete();
//        return response()->json([
//            'status' => '200',
//            'result' => null
//        ]);
//    }
}
