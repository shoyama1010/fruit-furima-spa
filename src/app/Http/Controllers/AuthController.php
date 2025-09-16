<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    // 新規登録
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            // 'password' => bcrypt($request->password),
            'password' => Hash::make($request->password),
        ]);

        // Sanctum のトークン発行
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '登録成功',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    // ログイン
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'ログイン失敗（認証できません）',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'ログイン成功',
            'user'    => $user,
            'token'   => $token,
        ], 200);

    }

    // // プロフィール取得（ログイン必須）
    // public function profile(Request $request): JsonResponse
    // {
    //     return response()->json([
    //         'message' => 'プロフィール情報',
    //         'user' => $request->user(),
    //     ], 200);
   
    // }
    
    // // ログアウト
    // public function logout(Request $request): JsonResponse
    // {
    //     $request->user()->currentAccessToken()->delete();

    //     return response()->json([
    //         'message' => 'ログアウトしました',
    //     ], 200);
    // }
}
