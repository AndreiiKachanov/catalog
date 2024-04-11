<?php

namespace App\Http\Requests\Admin\Items;

use App\Models\Admin\Item\Category;
use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    private $itemCategoriesTableName;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function __construct(
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {

        $this->itemCategoriesTableName = Category::getTableName();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'title' => 'required|string|max:255',
            'note' => 'max:255',
            'article_number' => 'required|string|max:100',
            'price' => 'required|numeric|min:1|max:1000000000',
            'min_order_amount' => 'nullable|numeric|min:1|max:1000000000',
            //'is_new' => 'required',
            //'is_hit' => 'required',
            //'is_bestseller' => 'required',
            'category' => "required|integer|exists:$this->itemCategoriesTableName,id",
            //            'img' => 'mimes:jpg,png,jpeg,gif,svg|max:1000|dimensions:min_width=100,min_height=100,max_width=500,max_height=500'
            'img' => 'required|mimes:jpg,png,jpeg,gif,svg'
        ];

        if ($this->getMethod() === 'PUT') {
            $rules['img'] = 'mimes:jpg,png,jpeg,gif,svg';
        }

         return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'title' => 'Название',
            'note' => 'Примечание',
            'article_number' => 'Артикул',
            'price' => 'Цена (₽)',
            'min_order_amount' => 'Минимальное количество для заказа',
            'is_new' => 'Новый',
            'is_hit' => 'Хит',
            'is_bestseller' => 'Бестселлер',
            'category' => 'Категория',
            'img' => 'Фото'
        ];
        return $attributes;
    }

    public function messages()
    {
        return [
            'required' => 'Поле <b>:attribute</b> обязательное для заполнения',
            'max' => 'Поле <b>:attribute</b> должно быть не более :max символов',
            'price.max' => '<b>:attribute</b> не должна превышать :max',
            'price.min' => '<b>:attribute</b> не должна быть меньше :min',
            'numeric' => 'Поле <b>:attribute</b> должно быть численным',
            'mimes' => '<b>Фото</b> должно быть файлом типа: jpg, png, jpeg, gif, svg'
        ];
    }
}
