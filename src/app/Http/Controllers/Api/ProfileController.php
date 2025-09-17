<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');
        // User モデルに profile リレーションを定義しておく

        return response()->json([
            // 'user' => $user,
            // 'name' => $user->name, // usersテーブルから
            // 'email' => $user->email,
            // 'profile' => $user->profile, // hasOneで取得
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'profile' => $user->profile, // ← phone_number 含めて返す
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // プロフィールが存在しなければ新規作成
        $profile = Profile::firstOrNew(['user_id' => $user->id]);

        $validated = $request->validate([
            'postcode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'img_url' => 'nullable|image|max:2048', // 画像必須ではない
        ]);

        // 画像アップロード処理
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('profiles', 'public');
            $validated['img_url'] = 'storage/' . $path;
        }

        // updateOrCreate で新規 or 更新
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'プロフィールを更新しました',
            'profile' => $profile,
        ]);
    }
}
