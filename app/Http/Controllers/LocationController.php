<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Base\IndexRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Spatie\QueryBuilder\QueryBuilder;

class LocationController extends Controller
{
     /**
     * @OA\Get(
     *      path="/locations",
     *      operationId="getLocations",
     *      tags={"Location"},
     *      summary="Get list of locations",
     *      description="Returns list of locations",
     *      security={ {"apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
                @OA\JsonContent(
    *             @OA\Examples(
    *                  example="result",
    *                  value={"data":{"name": "Шанхай"},{"name": "Валенсия"},{"name": "Торонто"},{"name": "Портленд (Орегон)"},{"name": "Варшава"},{"name": "Уилмингтон (Северная Каролина)"},"links":{"first":"http:\/\/127.0.0.1:8000\/api\/locations?page=1","last":"http:\/\/127.0.0.1:8000\/api\/locations?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":1,"last_page":1,"links":{"url":null,"label":"« Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/locations?page=1","label":"1","active":true},{"url":null,"label":"Next »","active":false},"path":"http:\/\/127.0.0.1:8000\/api\/locations","per_page":15,"to":6,"total":6}},
    *                  summary="An result object."),
    *         )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(IndexRequest $request)
    {
        $locations = QueryBuilder::for(Location::class)
            ->allowedIncludes(['user', 'freezingRooms', 'orders'])
            ->paginate($request->getPerPage())
            ->appends($request->query());

        return LocationResource::collection($locations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
