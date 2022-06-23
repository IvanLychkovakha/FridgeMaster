<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'freesingRooms' => $this->whenLoaded('freezingRooms'),
            'orders' => new OrderResource($this->whenLoaded("orders")),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
