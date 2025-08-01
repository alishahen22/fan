<?php

namespace App\Http\Requests\Api\User;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SignUpRequest extends FormRequest
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
            'name' => 'required|String|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:255|unique:users,phone',
            'city_id' => 'required|exists:cities,id',
            'password' => 'required|string|min:8',
            'customer_type' => 'required|in:individual,business',
            'commercial_register' => 'required_if:customer_type,business|digits:10',
           'commercial_register_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
'tax_number_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

            'tax_number' => 'nullable|digits:15',
            'owner_name' => 'required_if:customer_type,business|string|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $error = implode('- ', $validator->errors()->all());
        throw new HttpResponseException(
            msg(false, $error, ResponseAlias::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
