<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:tasks',
            'status_id' => 'required',
        ];
        if (Route::currentRouteName() === 'tasks.update') {
            $rules['name'] = [
                'required',
                Rule::unique('tasks', 'name')->ignore($this->task->id),
            ];
        }
        return $rules;
    }
}
