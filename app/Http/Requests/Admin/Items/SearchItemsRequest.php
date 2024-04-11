<?php

namespace App\Http\Requests\Admin\Items;

use Illuminate\Foundation\Http\FormRequest;

class SearchItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'search_input' => 'required|string|max:2'
        ];

        //if ($this->getMethod() === 'PUT') {
        //    $rules['img'] = 'mimes:jpg,png,jpeg,gif,svg';
        //}

        return $rules;
    }
}
