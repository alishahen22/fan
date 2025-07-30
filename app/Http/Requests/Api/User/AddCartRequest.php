<?php

namespace App\Http\Requests\Api\User;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AddCartRequest extends FormRequest
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
            "product_id" => "required|exists:products,id",
                'quantity' => [
                    'required',
                    'exists:product_quantities,id',
                    function ($attribute, $value, $fail) {
                        $productId = request('product_id');

                        $match = \DB::table('product_quantities')
                            ->where('id', $value)
                            ->where('product_id', $productId)
                            ->exists();

                        if (! $match) {
                            $fail('الكمية المختارة لا تنتمي لهذا المنتج.');
                        }
                    }
                ],
            'count' => ['required', "numeric", "min:1"],
            "options_selected" => "nullable|array",
            "options_selected.*" => "required|exists:product_attribute_options,id",
            "designs" => ["nullable", "array", Rule::requiredIf(Product::where('id', $this->product_id)->first()->category->type == 'printing')],
            "designs.*" => "required|file|mimes:jpeg,png,jpg,gif,svg,pdf,word|max:2048",
        ];
    }
}
