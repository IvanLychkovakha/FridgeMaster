<?php

namespace App\Http\Requests\Traits;
use Illuminate\Validation\Rule;
use App\Models\Block;

trait OrderTrait
{
    protected function getRules()
    {
        return [
            'temp' => ['numeric', 'between:-15,2'],
            'capacity' => ['numeric', 'min:1'],
            'price' => ['numeric', 'min:1'],
            'used_blocks' => ['array', 'min:0'],
            'used_blocks.*' => ['nullable','distinct', Rule::in(Block::all()->pluck('id'))],
            'location_id' => ['required', 'exists:locations,id'],
            'num_of_days' => ['required', 'integer', 'between:1,24'],
            
        ];
    }

}