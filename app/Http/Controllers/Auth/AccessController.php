<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
class AccessController extends Controller
{
        /**
     * @OA\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@gmail.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *             ),
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *             ),
     *             @OA\Examples(
     *                  example="result",
     *                  value={"user": {"id": 1,"name": "Dario Hills PhD","email": "test@gmail.com","email_verified_at": "2022-06-23T19:39:04.000000Z","created_at": "2022-06-23T19:39:04.000000Z","updated_at": "2022-06-23T19:39:04.000000Z"},"token": "2|NGDvenGJRVcfzJV8HmTrjTGzOUkFycEibKR2wAWl"},
     *                  summary="An result object."),
     *         )
     *     ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function store(LoginRequest $request)
    {
        if(Auth::attempt($request->validated())) {

            $user = Auth::user();

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response()->json($response);
        }

        throw new UnauthorizedHttpException('Basic');
    }

    /**
     * @OA\Delete(
     *      path="/auth/logout",
     *      operationId="logoutUser",
     *      tags={"Authentication"},
     *      summary="Logout",
     *      security={ {"apiAuth": {} }},

     *      description="Logout user",
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy()
    {
        Auth::user()->tokens()->delete();
    }

}
