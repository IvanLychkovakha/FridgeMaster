<?php

namespace App\Http\Requests\Traits;

trait UserTrait
{
    protected function getRules()
    {
        return [
            'name' => ['required','string','between:4,32'],
            'email' => ['required', 'email'],
            'password' => ['confirmed','required', 'string', 'between:5,32']
        ];
    }

}
