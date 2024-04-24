<?php

namespace App\Http\Requests\Admin\Items;

use App\Models\Admin\Item\Item;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'price_increase' => 'required|integer|min:0|max:1000',
            'price_regulation' => [
                'required',
                'integer',
                'min:-1000',
                'max:1000',
                function ($attribute, $value, $fail) {
                    $priceIncrease = (int)$value;
                    Item::all()->each(function ($item) use ($priceIncrease, $fail) {
                        $originalPrice = $item->getRawOriginal('price');
                        $newPrice = ($originalPrice / 100) * $priceIncrease + $originalPrice;
                        if ($newPrice <= 0) {
                            $fail('Ошибка: некоторые товары могут отобразиться с отрицательной ценой.');
                        }
                    });
                }
            ],
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
