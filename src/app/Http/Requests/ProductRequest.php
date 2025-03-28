<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        // return [
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'seasons' => 'required|array',
            'seasons.*' => 'exists:seasons,id',
        ];

        // 画像は登録時のみ必須（update時は不要）
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png|max:2048';
        } elseif ($this->isMethod('put')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png|max:2048';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => '商品名',
            'price' => '価格',
            'description' => '商品説明',
            'seasons' => '季節',
            'image' => '商品画像',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '価格を入力してください',
            'price.integer' => '価格は数値で入力してください',
            'price.between' => '価格は0〜10000円以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '説明文が120文字以内である必要があります',
            'seasons.required' => '季節を選択してください',
            'image.required' => '商品画像をアップロードしてください',
            'image.image' => '画像ファイルをアップロードしてください',
            'image.mimes' => '「.png」もしくは「.jpeg」形式でアップロードしてください',
        ];
    }
}
