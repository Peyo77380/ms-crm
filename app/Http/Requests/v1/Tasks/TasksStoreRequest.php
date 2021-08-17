<?php

namespace App\Http\Requests\V1\Tasks;

use App\Http\Requests\FormRequest;

class TasksStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|integer|max:3',
            'type' => 'required|integer|max:3',
            'title' => 'required|string|max:75',
            'content'=>'required|string|max:200',
            'admin_id'=>'required|integer',
            'user_id'=>'required|integer',
            'end_date'=>'required',
        ];
    }
}
