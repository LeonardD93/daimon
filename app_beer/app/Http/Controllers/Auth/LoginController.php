<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Logs in a user using either email or username along with a password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username", "password"},
     *             @OA\Property(property="username", type="string", example="root", description="User's email or username"),
     *             @OA\Property(property="password", type="string", format="password", example="password", description="User's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|qf2gkdsfksv", description="Generated API token"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 description="Details of the logged-in user",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="root"),
     *                 @OA\Property(property="email", type="string", format="email", example="root@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        $credentials = [
            filter_var($request->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name' => $request->input('username'),
            'password' => $request->input('password'),
        ];
    
        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('API Token')->plainTextToken;
 
            return response()->json([
                'token' => $token,
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
}
