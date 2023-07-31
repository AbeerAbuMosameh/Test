<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg',
            'name' => 'required|array',
            'name.*' => 'required|string|min:3',
            'sku' => 'required|string|unique:products,sku,' . $this->route('id'),
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'rate' => 'required|numeric|between:1,5',
            'status' => 'required|in:active,inactive',
            'description' => 'required|array',
            'description.*' => 'required|string|min:3',
        ];
    }
}
