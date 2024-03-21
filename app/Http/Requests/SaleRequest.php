<?php

namespace App\Http\Requests;

use App\Models\Product;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|integer|exists:clients,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail) {
                    $key = Str::replace('quantity', 'product_id', $attribute);
                    $productId = $this->input($key);
                    $product = Product::find($productId);

                    if ($product->stock < (int) $value) {
                        $fail("Stock insuficiente.");
                    }
                },
            ],
            'items.*.discount_percentage' => 'required|integer|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [
            'items.*.discount_percentage.max' => 'Máximo 100',
            'items.*.discount_percentage.min' => 'Mínimo 0',
        ];
    }
}
