<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'shipping_postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => 'required|string|max:255',
            'shipping_building' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_postal_code.required' => '郵便番号を入力してください',
            'shipping_postal_code.string' => '郵便番号は文字列で入力してください',
            'shipping_postal_code.size' => '郵便番号は「123-4567」の形式で入力してください',
            'shipping_postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'shipping_address.required' => '住所を入力してください',
            'shipping_address.string' => '住所は文字列で入力してください',
            'shipping_address.max' => '住所は255文字以内で入力してください',
        ];
    }
}
