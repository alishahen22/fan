<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProfileUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth('user')->user()->id,
            // 'company_name' => 'nullable|string|max:255',
            'job_name' => 'nullable|string|max:255',
            'value_added_certificate' => 'nullable|string|max:255',
            'value_added_certificate_file' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:20480',
            'city_id' => 'required|exists:cities,id',
            'phone' => 'required|string|unique:users,phone,' . auth('user')->user()->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
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
            msg(false, $error, failed())
        );
    }
}
