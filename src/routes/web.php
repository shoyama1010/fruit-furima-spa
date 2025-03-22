<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [ProductController::class, 'index']);             // 商品一覧
Route::get('/products/{id}', [ProductController::class, 'show']);         // 商品詳細
Route::get('/products/{id}/update', [ProductController::class, 'edit']);  // 商品編集画面
Route::post('/products/{id}/update', [ProductController::class, 'update']); // 商品更新
Route::get('/products/register', [ProductController::class, 'create']);   // 商品登録画面
Route::post('/products/register', [ProductController::class, 'store']);   // 商品登録処理
Route::get('/products/search', [ProductController::class, 'search']);     // 商品検索
Route::post('/products/{id}/delete', [ProductController::class, 'destroy']); // 商品削除
