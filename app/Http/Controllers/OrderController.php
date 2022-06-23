<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Http\Requests\Base\IndexRequest;
use App\Http\Requests\Order\CreateRequest;
use App\Http\Requests\Order\CalculatePriceRequest;
use App\Models\Block;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

     /**
     * @OA\Get(
     *      path="/orders",
     *      operationId="getUserOrdersList",
     *      tags={"Order"},
     *      summary="Get list of user orders",
     *      description="Returns list of user orders",
     *      security={ {"apiAuth": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
                @OA\JsonContent(
    *            
    *             @OA\Examples(
    *                  example="result",
    *                  value={"data":{"id":1,"uid":"rQa651SKNspQ","status":"booked","price":260,"used_blocks":"[1,2,3,4,5,6,7,8,9,10,11,12,13]","user_id":1,"created_at":"2022-06-23T21:11:10.000000Z","updated_at":"2022-06-23T21:11:10.000000Z"},"links":{"first":"http:\/\/127.0.0.1:8000\/api\/orders?page=1","last":"http:\/\/127.0.0.1:8000\/api\/orders?page=1","prev":null,"next":null},"meta":{"current_page":1,"from":1,"last_page":1,"links":{"url":null,"label":"&laquo; Previous","active":false},{"url":"http:\/\/127.0.0.1:8000\/api\/orders?page=1","label":"1","active":true},{"url":null,"label":"Next &raquo;","active":false},"path":"http:\/\/127.0.0.1:8000\/api\/orders","per_page":15,"to":1,"total":1}},
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
        $orders = QueryBuilder::for(Auth::user()->orders())
            ->allowedFilters(['status', 'created_at'])
            ->allowedIncludes(['user'])
            ->paginate($request->getPerPage())
            ->appends($request->query());

        return OrderResource::collection($orders);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     tags={"Order"},
     *     summary="Create an orders",
     *     security={ {"apiAuth": {} }},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="location_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="num_of_days",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="decimal"
     *                 ),
     *                 @OA\Property(
     *                     property="used_blocks",
     *                     type="array",
     *                     @OA\Items(type="integer")
     *                 ),
     *                 example={"location_id": 1, "num_of_days": 10, "price": 260, "used_blocks": "[1,2,3,4,5,6,7,8,9,10,11,12,13]"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function store(CreateRequest $request)  
    {
        $data = $request->validated();

        DB::beginTransaction();

        $order = new Order($data);
        $order->user()->associate(Auth::user());
        $order->location()->associate(Location::find($data['location_id']));
        $order->status = 'booked';
        $order->uid = Str::random(12);
        $order->save();

        foreach(Block::whereIn('id', $data['used_blocks'])->get() as $item)
        {
            $item->empty = false;
            $item->save();
        }

        DB::commit();


    }

    /**
     * @OA\Post(
     *     path="/orders/calculatePrice",
     *     tags={"Order"},
     *     summary="Calculate price",
     *     security={ {"apiAuth": {} }},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="location_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="capacity",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="temp",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="num_of_days",
     *                     type="decimal"
     *                 ),
     *                 example={"location_id": 1, "capacity": 25, "temp": -1, "num_of_days": 10}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="num_of_blocks",
     *                 type="integet",
     *             ),
     *             @OA\Property(
     *                 property="location_id",
     *                 type="integer",
     *             ),
     *              @OA\Property(
     *                 property="used_blocks",
     *                 type="array",
     *                 @OA\Items(type="integer")
     *             ),
     *             @OA\Property(
     *                 property="num_of_days",
     *                 type="array",
     *                 @OA\Items(type="integer")
     *             ),
     *              @OA\Property(
     *                 property="price",
     *                 type="array",
     *                 @OA\Items(type="decimal")
     *             ),
     *             @OA\Examples(
     *                  example="result",
     *                  value={"num_of_blocks":13,"location_id":"1","used_blocks":"[14,15,16,17,18,19,20,21,22,23,24,25,26]","num_of_days":"10","price":260},
     *                  summary="An result object."),
     *         )
     *     )
     * ),
     * 
     */
    public function calculatePrice(CalculatePriceRequest $request) // location, capacity, temp, num_of_days
    {
        $data = $request->validated();

        $numOfNeededBlocks = round($data['capacity'] / 2);
        $numOfAvailBlocks = 0;
        $blockIds = collect([]);

        $freezingRooms = Location::find($data['location_id'])
                        ->freezingRooms()
                        ->with(['blocks' => function($q) {
                            $q->where('empty', true);
                        }])
                        ->where('temp', '<', 0)
                        ->whereBetween('temp', [$data['temp'] - 2, ($data['temp'] >= -2 ? 0 : $data['temp'] + 2)])
                        ->get();


        foreach($freezingRooms as $item)
        {
            $availBlocks = $item->blocks;
            $countOfAvailBlocks = count($availBlocks);

            if($countOfAvailBlocks + $numOfAvailBlocks >= $numOfNeededBlocks) 
            {
                $numOfAvailBlocks += $countOfAvailBlocks;
                $blockIds = $blockIds->merge($availBlocks->slice(0, $countOfAvailBlocks - ( $numOfNeededBlocks - ($countOfAvailBlocks + $numOfAvailBlocks)) )->pluck('id'));
                break;
            }

            $blockIds = $blockIds->merge($availBlocks->pluck('id'));

            $numOfAvailBlocks += $countOfAvailBlocks;
        }

        if($numOfAvailBlocks >= $numOfNeededBlocks)
        {
            if(count($blockIds) > $numOfNeededBlocks) $blockIds->splice( $numOfNeededBlocks);

            return response([
                'num_of_blocks' => $numOfNeededBlocks,
                'location_id' => $data['location_id'],
                'used_blocks' => $blockIds->toArray(),
                'num_of_days' => $data['num_of_days'],
                'price' => $data['num_of_days'] * count($blockIds) * 2,
            ]);
        }

        return response([
            'error' => "This location does not have the required number of blocks. Required quantity $numOfNeededBlocks pieces"
        ]);
    }

  
    /**
     * @OA\Get(
     *      path="/orders/{id}",
     *      operationId="getOrderById",
     *      tags={"Order"},
     *      summary="Get order information",
     *      description="Returns order data",
     *      @OA\Parameter(
     *          name="id",
     *          description="order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Examples(
     *                  example="result",
     *                  value={"data":{"id":1,"uid":"rQa651SKNspQ","status":"booked","price":260,"used_blocks":"[1,2,3,4,5,6,7,8,9,10,11,12,13]","user_id":1,"created_at":"2022-06-23T21:11:10.000000Z","updated_at":"2022-06-23T21:11:10.000000Z"}},
     *                  summary="An result object."),
     *          )
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
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

}
