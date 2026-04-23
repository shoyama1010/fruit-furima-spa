<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');

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

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        // users テーブル更新
        $user->update([
            'name' => $validated['name'],
        ]);

        $profileData = [
            'postcode' => $validated['postcode'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
        ];

        $existingProfile = $user->profile;

        if ($request->hasFile('img_url')) {
            // 古い画像削除
            if ($existingProfile?->img_url) {
                $oldPath = str_replace('storage/', '', $existingProfile->img_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('img_url')->store('profiles', 'public');
            $profileData['img_url'] = 'storage/' . $path;
        }

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
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
        ], 200);
    }
}
