<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // 'postcode' => ['nullable', 'digits:7'],
            'postcode' => ['required', 'digits:7'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'regex:/^[0-9]+$/', 'max:20'],
            'img_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は255文字以内で入力してください。',

            'postcode.required' => '郵便番号を入力してください。',
            
            'postcode.digits' => '郵便番号は7桁の数字で入力してください。',

            'address.max' => '住所は255文字以内で入力してください。',

            'phone_number.regex' => '電話番号は半角数字で入力してください。',
            'phone_number.max' => '電話番号は20文字以内で入力してください。',

            'img_url.image' => 'プロフィール画像は画像ファイルを選択してください。',
            'img_url.mimes' => 'プロフィール画像は jpeg, png, jpg, webp 形式でアップロードしてください。',
            'img_url.max' => 'プロフィール画像は2MB以内でアップロードしてください。',
        ];
    }
}

