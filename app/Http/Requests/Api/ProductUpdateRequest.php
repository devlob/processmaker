<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'code' => 'required',
            'status' => ['required', 'boolean'],
        ];
    }
}
