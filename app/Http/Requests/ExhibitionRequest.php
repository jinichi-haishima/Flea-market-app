<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'condition_id' => 'required|exists:item_conditions,id',
            'image' => 'required|image|mimes:jpeg,png|max:2048',
        ];
    }

    public function messages(): array {
        return [
            'name.required' => '商品名は必須です。',
            'description.required' => '商品の説明は必須です。',
            'price.required' => '販売価格は必須です。',
            'price.min' => '販売価格は0円以上に設定してください。',
            'category_id.required' => 'カテゴリーは必須です。',
            'condition_id.required' => '商品の状態は必須です。',
            'image.required' => '画像は必須です。',
            'image.image' => '画像ファイルを選択してください。',
            'image.mimes' => '画像はJPEGまたはPNG形式でアップロードしてください。',
            'image.max' => '画像のサイズは2MB以下にしてください。',
        ];
    }
}
