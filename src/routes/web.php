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

// 商品一覧・検索
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');

// 商品登録画面＆処理
Route::get('/products/register', [ProductController::class, 'create'])->name('products.create');
Route::post('/products/register', [ProductController::class, 'store'])->name('products.store');

// 編集（パスを /edit に変更）
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
// Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');

// 商品詳細（1つだけ）
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
// 削除
Route::delete('/products/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
