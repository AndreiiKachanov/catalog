<?php

namespace App\Http\Requests;

use App\Models\Admin\Cart\Order\Order;
use App\Models\Admin\Cart\Token;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SetOrderInfoRequest extends FormRequest
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
        return [
            'token' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    //токен должен быть 32 символа и в бд
                    if ( !(Str::length($value) === 32 && Token::firstWhere('token', $value)) ) {
                        $fail($attribute . ' not valid');
                    }
                }
            ],
            'field' => function ($attribute, $value, $fail) {
                $tableName = Order::getTableName();
                $columnNames = Schema::getColumnListing($tableName);
                $excludedColumns = ['id', 'token_id', 'created_at', 'updated_at', 'deleted_at'];
                $filteredColumns = array_diff($columnNames, $excludedColumns);
                if (!in_array($value, $filteredColumns)) {
                    $fail($value . ' not valid');
                }
            },
            'value' => [
                'nullable',
                'string',
                'max:255'
            ]
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
}
