<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\OrderTrait;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    use OrderTrait;

    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $usedBlocks = json_decode($this->used_blocks);

        $this->request->remove('used_blocks');

        $this->merge([
            'used_blocks' => $usedBlocks ?? []
        ]);

    }

    public function rules()
    {
        $rules = $this->getRules();

        array_unshift($rules['temp'], 'nullable');
        array_unshift($rules['capacity'], 'nullable');
        array_unshift($rules['price'], 'required');
        array_unshift($rules['used_blocks'], 'required');
        
        return $rules;
    }

}
