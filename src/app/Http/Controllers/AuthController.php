<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $result = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->profile()->create([
                'postcode'     => null,
                'address'      => null,
                'phone_number' => null,
                'img_url'      => null,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });

        return response()->json([
            'message' => '登録成功',
            'user'    => $result['user'],
            'token'   => $result['token'],
        ], 201);
    }
    // public function register(Request $request): JsonResponse
    // {
    //     $request->validate([
    //         'name'     => 'required|string|max:255',
    //         'email'    => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     $user = User::create([
    //         'name'     => $request->name,
    //         'email'    => $request->email,
    //         // 'password' => bcrypt($request->password),
    //         'password' => Hash::make($request->password),
    //     ]);

    //     // Sanctum のトークン発行
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     // プロフィールの初期レコードを作成
    //     $user->profile()->create([
    //         'postcode' => null,
    //         'address' => null,
    //         'phone_number' => null,
    //         'img_url' => null,
    //     ]);

    //     return response()->json([
    //         'message' => '登録成功',
    //         'user'    => $user,
    //         'token'   => $token,
    //     ], 201);
    // }

    // ログイン
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'メールアドレスまたはパスワードが正しくありません',
            ], 401);
        }

        // 同じ端末名の古いトークンを削除したい場合
        $user->tokens()->where('name', 'auth_token')->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'ログイン成功',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    // public function login(Request $request): JsonResponse
    // {
    //     $request->validate([
    //         'email'    => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return response()->json([
    //             'message' => 'ログイン失敗（認証できません）',
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     $token = $user->createToken('auth_token')->plainTextToken;

    //     return response()->json([
    //         'message' => 'ログイン成功',
    //         'user'    => $user,
    //         'token'   => $token,
    //     ], 200);

    // }


    // ログアウト
    public function logout(Request $request): JsonResponse
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'ログアウトしました',
        ], 200);
        // $request->user()->currentAccessToken()->delete();

        // return response()->json([
        //     'message' => 'ログアウトしました',
        // ], 200);
    }
}
