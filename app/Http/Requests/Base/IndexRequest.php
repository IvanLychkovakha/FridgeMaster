<?php

namespace App\Http\Requests\Base;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{

    const DEFAULT_PER_PAGE = 15;

    const MAX_PER_PAGE = 200;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'perPage' => [
                'nullable',
                'numeric',
                'min:1',
                'max:' . static::MAX_PER_PAGE,
            ],
        ];
    }

    /**
     * Return per page
     *
     * @return int
     */
    public function getPerPage()
    {

        return $this->query('perPage') ?? static::DEFAULT_PER_PAGE;
    }
}