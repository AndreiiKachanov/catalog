<?php

namespace App\Http\Requests\Admin\Items;

use App\Models\Admin\Item\Category;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        $categoriesTabName = Category::getTableName();
        $rules = [
            'title' => 'required|string|max:255',
            'parent' => "nullable|exists:$categoriesTabName,id",
            'img' => 'mimes:jpg,png,jpeg,gif,svg'
        ];

        //если обновляем модель
        if ($this->getMethod() === 'PUT') {
            $rules['parent'] = [
                'nullable',
                "exists:$categoriesTabName,id",
                function ($attribute, $value, $fail) {
                    $currentCat = $this->route('category');
                    $selectCat = Category::find($value);
                    if ($selectCat !== null && $selectCat->isDescendantOf($currentCat)) {
                        $fail('Родителем данной категории не может быть её же потомок');
                    }
                    if ($selectCat !== null && $selectCat->id === $currentCat->id) {
                        $fail('Нельзя выбирать самого себя в качестве родителя');
                    }
                }
            ];
        }
        return $rules;
    }

    public function attributes(): array
    {
        return [
            'title' => 'Название категории',
            'parent' => 'Родительская категория',
            'img' => 'Фото'
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле <b>:attribute</b> обязательное для заполнения',
            'mimes' => '<b>Фото</b> должно быть файлом типа: jpg, png, jpeg, gif, svg'
        ];
    }
}
