<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
            'payment_selection' => 'required|string',
            'shipping_address' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_selection.required' => '支払方法を選択してください',
            'payment_selection.string' => '支払方法の形式が正しくありません',
            'shipping_address.string' => '配送先住所の形式が正しくありません',
            'shipping_address.max' => '配送先住所は255文字以内で入力してください',
        ];
    }
}
