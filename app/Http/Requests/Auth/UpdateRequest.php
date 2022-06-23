<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\UserTrait;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use UserTrait;

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = $this->getRules();
        
        array_push($rules['name'],  Rule::unique('users')->ignore($this->user));
        array_push($rules['email'],  Rule::unique('users')->ignore($this->user));

        return $rules;
    }
}
