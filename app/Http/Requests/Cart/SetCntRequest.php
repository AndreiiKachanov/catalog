<?php

namespace App\Http\Requests\Cart;

use App\Models\Admin\Cart\Token;
use App\Models\Admin\Item\Item;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetCntRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $cartTokensTableName = Token::getTableName();
        $itemsTableName = Item::getTableName();

        return [
            'token' => "required|size:32|exists:$cartTokensTableName,token",
            'id' => "required|exists:$itemsTableName,id",
            'cnt' => 'required|integer|min:1|max:65535',
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ])->setStatusCode(422));
    }
}
