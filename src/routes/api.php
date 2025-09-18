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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 商品一覧表示
Route::get('/products', [ProductController::class, 'apiIndex']);

// 詳細ページ遷移表示
Route::get('/products/{id}', [ProductController::class, 'apiShow']);

// 商品更新＆削除
Route::post('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'apiDestroy']);

// ユーザー登録
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);
});

// 季節
Route::get('/seasons', function () {
    return response()->json(Season::all());
});

// 商品登録
Route::post('/products/register', [ProductController::class, 'store']);

Route::post('/products', [ProductController::class, 'apiStore'])->name('api.products.store');