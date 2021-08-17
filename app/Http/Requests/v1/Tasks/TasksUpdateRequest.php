<?php

namespace App\Http\Requests\V1\Tasks;

use App\Http\Requests\FormRequest;

class TasksUpdateRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'integer|max:3',
            'type' => 'integer|max:3',
            'title' => 'string|max:75',
            'content'=>'string|max:200',
            'admin_id'=>'integer',
            'user_id'=>'integer',
            'end_date'=>'required',
        ];
    }
}
