<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Traits\UserTrait;

class LoginRequest extends FormRequest
{
    use UserTrait;

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        $rules = $this->getRules();
        array_shift($rules);
        array_shift($rules['password']);
        return $rules;
    }
}
