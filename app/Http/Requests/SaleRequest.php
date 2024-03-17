<?php

namespace App\Http\Requests;

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
            'items.*.quantity' => 'required|integer|min:1',
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
