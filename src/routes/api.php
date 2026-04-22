<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Season;

// 認証不要
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 商品一覧・詳細は公開
Route::get('/products', [ProductController::class, 'apiIndex']);
Route::get('/products/{id}', [ProductController::class, 'apiShow']);

// 季節
Route::get('/seasons', function () {
    return response()->json(Season::all());
});

// 認証が必要
Route::middleware('auth:sanctum')->group(function () {
    // ログインユーザー取得
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // 認証チェック用（必要なら残す）
    Route::get('/check-auth', function (Request $request) {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user(),
        ]);
    });

    // ログアウト
    Route::post('/logout', [AuthController::class, 'logout']);

    // プロフィール
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // 商品登録・更新・削除
    Route::post('/products', [ProductController::class, 'apiStore'])->name('api.products.store');
    // Route::put('/products/{id}', [ProductController::class, 'update'])->name('api.products.update');
    Route::put('/products/{id}', [ProductController::class, 'apiUpdate'])->name('api.products.update');

    Route::delete('/products/{id}', [ProductController::class, 'apiDestroy'])->name('api.products.destroy');
});
