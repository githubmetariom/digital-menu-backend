<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @OA\Info(
 *     title="food_menu",
 *     version="0.1"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 * @OA\OpenApi(
 *   security={
 *     {"apiAuth":{}}
 *   }
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @OA\Get(
     *     path="/api/ping",
     *   @OA\Response(
     *     @OA\MediaType(mediaType="application/json"),
     *     response=200,
     *     description="My Response"
     *   ),
     *   @OA\Response(
     *     @OA\MediaType(mediaType="application/json"),
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    #[ArrayShape(['pong' => "string"])] public function ping(): array
    {
        return ['pong' => 'ok'];
    }
}
