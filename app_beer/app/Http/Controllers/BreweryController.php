<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BreweryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/breweries",
     *     tags={"Breweries"},
     *     summary="Get paginated breweries list",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number (default: 1)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page (default: 10)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             default=10
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             ),
     *             @OA\Property(property="meta", type="object"),
     *             @OA\Property(property="links", type="object")
     *         )
     *     )
     * )
    */

    public function index(Request $request)
    {
        $params = [
            'page' => $request->get('page', 1),
            'per_page' => $request->get('per_page', 10),
        ];
        $response = Http::get('https://api.openbrewerydb.org/breweries', $params);

        return response()->json($response->json());
    }
}