<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\OrderTrait;
use Illuminate\Validation\Rule;


class CalculatePriceRequest extends FormRequest
{
    use OrderTrait;

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = $this->getRules();

        array_unshift($rules['location_id'], 'required');
        array_unshift($rules['temp'], 'required');
        array_unshift($rules['capacity'], 'required');
        array_unshift($rules['price'], 'nullable');
        array_unshift($rules['used_blocks'], 'nullable');
        return $rules;
    }

}