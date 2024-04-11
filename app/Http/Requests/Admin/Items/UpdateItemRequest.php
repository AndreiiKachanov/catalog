<?php

namespace App\Http\Requests\Admin\Items;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
        return [
            'title' => 'required|string|max:255',
            'note' => 'max:255',
            'article_number' => 'required|string|max:100',
            'price' => 'required|string|max:100',
//            'img' => 'mimes:jpg,png,jpeg,gif,svg|max:1000|dimensions:min_width=100,min_height=100,max_width=500,max_height=500'
            'img' => 'mimes:jpg,png,jpeg,gif,svg'
        ];
    }
}
