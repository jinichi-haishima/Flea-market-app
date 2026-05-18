<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'profile_image_url' => 'nullable|image|mimes:jpeg,png|max:2048',
            'name' => 'required|string|max:20',
            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image_url.image' => 'プロフィール画像は画像ファイルでなければなりません。',
            'profile_image_url.mimes' => 'プロフィール画像はJPEGまたはPNG形式でなければなりません。',
            'profile_image_url.max' => 'プロフィール画像のサイズは2MB以下でなければなりません。',
            'name.required' => '名前は必須です。',
            'name.string' => '名前は文字列でなければなりません。',
            'name.max' => '名前は20文字以下でなければなりません。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.string' => '郵便番号は文字列でなければなりません。',
            'postal_code.size' => '郵便番号は8文字でなければなりません。',
            'postal_code.regex' => '郵便番号の形式が正しくありません。例: 123-4567',
            'address.required' => '住所は必須です。',
            'address.string' => '住所は文字列でなければなりません。',
            'address.max' => '住所は255文字以下でなければなりません。',
        ];
    }
}
