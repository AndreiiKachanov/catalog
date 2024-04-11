<?php

namespace App\Http\Requests\Admin\Items;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'price_increase' => 'required|integer|min:0|max:1000',
            //'price_increase2' => 'required|integer|min:-100|max:100',
            'min_order_cost' => 'nullable|numeric|min:1|max:1000000',
            'phone_number' => 'nullable|max:255',
            'instagram' => 'nullable|max:255',
            'whatsapp' => 'nullable|max:255',
            'site' => 'nullable|max:255',
            'viber' => 'nullable|max:255',
            'tiktok' => 'nullable|max:255',
            'youtube' => 'nullable|max:255',
            'custom_text' => 'nullable|max:255'
        ];
    }
}
