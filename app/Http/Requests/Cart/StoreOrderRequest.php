<?php

namespace App\Http\Requests\Cart;

use App\Models\Admin\Cart\Token;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        try {
            $cartTokensTableName = Token::getTableName();

            return [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:255',
                'transport_company' => 'required|string|max:255',
                'token' => "required|size:32|exists:$cartTokensTableName,token",
                'items' => [
                    'required',
                    'array',
                    'min:1',
                    //Сравниваем items переданные из корзины с items в бд
                    function ($attribute, $value, $fail) {
                        $dbArr = Token::firstWhere('token', $this->token)->rCartItems->toArray();
                        $frontArr = $value;

                        if (count($dbArr) !== count($frontArr)) {
                            $fail('The length attribute `' . $attribute . '` !== length cart items in database');
                        } else {
                            foreach ($dbArr as $k => $v) {
                                if (!($dbArr[$k]['id'] === $frontArr[$k]['id'] && $dbArr[$k]['cnt'] === $frontArr[$k]['cnt'])) {
                                    $fail('The attribute `' . $attribute . '` not equaled with database');
                                }
                            }
                        }
                    }
                ]
            ];
        } catch (QueryException $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'required' => 'The <b>:attribute</b> field is required',
            'items.min' => 'The :attribute must be at least :min',
            'items.*.id' => 'The :attribute not exists in database'
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    public function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ])->setStatusCode(422));
    }
}
