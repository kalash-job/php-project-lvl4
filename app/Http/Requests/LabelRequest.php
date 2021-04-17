<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LabelRequest extends FormRequest
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
        $route = $this->route() ? $this->route()->getName() : '';
        if ($route === 'labels.update') {
            $rules['name'] = [
                'required',
                Rule::unique('labels', 'name')->ignore($this->label->id),
            ];
        }
        return $rules;
    }
}
