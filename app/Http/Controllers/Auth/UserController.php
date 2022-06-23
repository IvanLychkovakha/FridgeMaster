<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\UpdateRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/auth/user",
     *      operationId="getUserProfile",
     *      tags={"Profile"},
     *      summary="Get user profile",
     *      description="Returns user profile",
     *      security={ {"apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function show()
    {
        return Auth::user();
    }

    /**
     * @OA\Put(
     *      path="/auth/user",
     *      operationId="updateUser",
     *      tags={"Profile"},
     *      summary="Update user data",
     *      description="Returns updated user data",
     *      security={ {"apiAuth": {} }},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "test@gmail.com", "name":"testName","password": "password"}
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *          response=202,
     *          description="Successful operation",
    *        @OA\JsonContent(
    *             @OA\Property(
    *                 property="user",
    *                 type="object",
    *             ),
    *            
    *             @OA\Examples(
    *                  example="result",
    *                  value={"user": {"id": 1,"name": "Dario Hills PhD","email": "test@gmail.com","email_verified_at": "2022-06-23T19:39:04.000000Z","created_at": "2022-06-23T19:39:04.000000Z","updated_at": "2022-06-23T19:39:04.000000Z"}},
    *                  summary="An result object."),
    *         )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
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
    public function update(UpdateRequest $request)
    {

        $user = Auth::user();
        $user->update($request->validated());
        return $user;
    }

}
