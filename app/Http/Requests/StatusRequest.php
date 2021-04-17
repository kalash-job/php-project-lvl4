<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
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
            'name' => 'required|unique:statuses',
        ];
        if (Route::currentRouteName() === 'task_statuses.update') {
            $rules['name'] = [
                'required',
                Rule::unique('statuses', 'name')->ignore($this->task_status->id),
            ];
        }
        return $rules;
    }
}
