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
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'postcode' => $user->profile?->postcode,
            'address' => $user->profile?->address,
            'phone_number' => $user->profile?->phone_number,
            'img_url' => $user->profile?->img_url,
            
        ]);
    }
    public function update(Request $request)
    {
        $user = $request->user();
        // プロフィールが存在しなければ新規作成
        // $profile = Profile::firstOrNew(['user_id' => $user->id]);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'postcode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'img_url' => 'nullable|image|max:2048', // 画像必須ではない
        ]);

        // users テーブルの name 更新
        $user->update([
            'name' => $validated['name'],
        ]);

        // profiles テーブル用データ
        $profileData = [
            'postcode' => $validated['postcode'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ];

        // 画像アップロード処理
        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('profiles', 'public');
            $profileData['img_url'] = 'storage/' . $path;
        }

        // updateOrCreate で新規 or 更新
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            // $validated
            $profileData
        );

        return response()->json([
            'message' => 'プロフィールを更新しました',
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'postcode' => $profile->postcode,
                'address' => $profile->address,
                'phone_number' => $profile->phone_number,
                'img_url' => $profile->img_url,
            ],
            // 'message' => 'プロフィールを更新しました',
            // 'profile' => $profile,
        ]);
    }
}
