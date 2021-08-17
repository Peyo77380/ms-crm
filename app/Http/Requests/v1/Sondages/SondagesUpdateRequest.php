<?php

namespace App\Http\Requests\V1\Sondages;

use App\Http\Requests\FormRequest;

class SondagesUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'question' => 'required|string',
            'type' => 'required|integer',
            'answer'=>'required|string',
            'start'=>'required|date',
            'end'=>'required|date',
            'state'=>'required|integer'
        ];
    }
}
