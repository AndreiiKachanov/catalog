<?php

namespace App\Http\Requests\Cart;

use App\Models\Admin\Cart\Token;
use App\Models\Admin\Item\Item;
use DomainException;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Monolog\Logger;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
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


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ])->setStatusCode(422));

    }
    //protected function failedValidation(Validator $validator)
    //{
    //    if ($this->wantsJson()) {
    //        dd(1);
    //        throw new ValidationException($validator, response()->json([
    //            'message' => 'Ошибка валидации',
    //            'errors' => $validator->errors(),
    //        ], 422));
    //    }

        //parent::failedValidation($validator);
    //}

    //protected function prepareForValidation()
    //{
    //    try {
    //        // Ваша предварительная обработка данных
    //        // Например, обращение к базе данных
    //        $token = $this->input('token');
    //        if (!(Str::length($token) === 32 && Token::firstWhere('token', $token))) {
    //            throw new DomainException('Token not valid');
    //        }
    //
    //        $itemId = $this->input('id');
    //        if (!Item::firstWhere('id', $itemId)) {
    //            throw new DomainException('Item not valid');
    //        }
    //    } catch (QueryException $e) {
    //            $errorMsg = sprintf("Error in %s, line %d. %s", __METHOD__, __LINE__, $e->getMessage());
    //            Log::error($errorMsg);
    //            throw new HttpResponseException(response()->json([
    //                'message'   => 'Database Error. See logs.',
    //            ])->setStatusCode(500));
    //    }
    //    catch (DomainException $e) {
    //        throw new HttpResponseException(response()->json([
    //            'success'   => false,
    //            'message'   => 'Validation errors',
    //            'data'      => $e->getMessage()
    //        ])->setStatusCode(422));
    //    }
    //}
}
