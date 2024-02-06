<?php

namespace Modules\General\app\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Modules\Financial\Enumeration\TaxEnum;
use Modules\General\app\Models\Enumeration;
use Modules\Shop\app\Http\Requests\LanguageRequest;

class BaseGeneralController extends Controller
{
    /**
     * @OA\post(
     *     path="/api/setting/v1/change-default-language",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Setting"
     *     },
     * )
     */
    public function changeDefaultLanguage(Request $request): JsonResponse
    {
        // not work😢😢😢
        $supportedLanguages = config('languages.supported');
        $request->validate(['lang' => ['required', 'in:' . implode(',', $supportedLanguages)]]);
        session(['language' => $request->lang]);
        if ($request->lang) {
            app()->setLocale($request->input('lang'));
        } else {
            app()->setLocale(config('app.locale'));
        }
        return response()->json(['message' => 'Language changed successfully']);

    }

    /**
     * @OA\get(
     *     path="/api/setting/v1/tax-rate",
     *     @OA\Response(response="200", description="Successful operation"),
     *     @OA\Parameter(
     *         name="lang",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *        tags={
     *          "Setting"
     *     },
     * )
     */
    public function changeTaxRate(Request $request): JsonResponse
    {
        $request->validate([
            'rate' => ['required', 'integer']
        ]);

        $taxRate = Enumeration::parentIdFilter(TaxEnum::PARENT)->first();
        $taxRate->update([
            'title' => $request->rate
        ]);
        return response()->json(['message' => 'tax rate changed successfully']);

    }


}
