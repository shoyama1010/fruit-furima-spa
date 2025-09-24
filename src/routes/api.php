<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Models\Season;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});
// Sanctum 認証が必要なルート
Route::middleware('auth:sanctum')->group(function () {
    // ログインユーザー情報
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
    // 認証チェック用
    Route::get('/check-auth', function (Request $request) {
        return response()->json([
            'authenticated' => true,
            'user' => $request->user(),
        ]);
    });
});

// 認証なしで仮のレスポンス
Route::get('/check-auth', function () {
    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => 1,
            'name' => 'Guest User',
            'email' => 'guest@example.com',
        ],
    ]);
});


// 商品一覧表示
Route::get('/products', [ProductController::class, 'apiIndex']);

// 詳細ページ遷移表示
Route::get('/products/{id}', [ProductController::class, 'apiShow']);

// Route::middleware('auth:sanctum')->group(function () {

// 商品登録（CSRF/ログインなし）
Route::post('/products', [ProductController::class, 'apiStore'])->name('api.products.store');
// 商品更新
Route::put('/products/{id}', [ProductController::class, 'update'])->name('api.products.update');;

// 商品削除
Route::delete('/products/{id}', [ProductController::class, 'apiDestroy'])->name('api.products.destroy');
// });

// テスト用
// Route::middleware('auth:sanctum')->get('/check-auth', function (Request $request) {
//     return response()->json(['user' => $request->user()]);
// });

// ユーザー登録
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/profile', [ProfileController::class, 'show']);
Route::post('/profile', [ProfileController::class, 'update']);
// });

// 季節
Route::get('/seasons', function () {
    return response()->json(Season::all());
});

// 商品登録
Route::post('/products/register', [ProductController::class, 'store']);

Route::post('/products', [ProductController::class, 'apiStore'])->name('api.products.store');
