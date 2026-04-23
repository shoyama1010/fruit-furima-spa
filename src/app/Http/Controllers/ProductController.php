<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {

        $products = Product::with('seasons')->latest()->paginate(6);
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    public function store(ProductRequest $request)
    {
        $path = $request->file('image') ? $request->file('image')->store('products', 'public') : null;

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $path,
        ]);


        $product->seasons()->sync($request->seasons);

        return redirect('/products');
    }

    public function edit($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        $seasons = Season::all();
        return view('products.edit', compact('product', 'seasons'));
    }

    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->update($request->only(['name', 'price', 'description']));
        $product->seasons()->sync($request->seasons);

        return redirect('/products');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // 関連する画像ファイルも削除する場合
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect('/products')->with('success', '商品を削除しました。');
    }

    public function search(Request $request)
    {
        $query = Product::with('seasons');

        // 検索ワード
        if ($request->filled('keyword')) {
            $query->where('name', 'like', "%{$request->keyword}%");
        }

        // ソート処理
        if ($request->sort === 'high') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'low') {
            $query->orderBy('price', 'asc');
        } else {
            $query->latest(); // デフォルト（新着順）
        }

        // $products = $query->get();
        $products = $query->paginate(6);
        return view('products.index', compact('products'));
    }

    public function apiIndex(Request $request)
    {
        $query = Product::with('seasons');

        // 🔍 検索機能
        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // ↕ ソート機能
        if ($request->sort === 'high') {
            $query->orderBy('price', 'desc');
        } elseif ($request->sort === 'low') {
            $query->orderBy('price', 'asc');
        } else {
            $query->latest();
        }

        // 📄 ページネーション（1ページ 9 件）
        $products = $query->paginate(9);

        return response()->json($products);
    }

    public function apiShow($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        return response()->json($product);
    }

    public function apiStore(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        // $product->user_id = $request->user()->id; // ログインユーザーに紐づける
        $product->user_id = 1; // ★仮で固定（ログイン不要にする）

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->save();

        // 中間テーブル seasons を同期
        $product->seasons()->sync($request->input('seasons', []));

        return response()->json(['message' => '商品を登録しました', 'product' => $product], 201);
    }

    public function apiUpdate(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image = $path;
        }

        $product->update($request->only(['name', 'price', 'description']));
        $product->seasons()->sync($request->seasons);

        return response()->json([
            'message' => '商品を更新しました',
            'product' => $product->load('seasons'),
        ], 200);
    }

    public function apiDestroy($id)
    {
        $product = Product::findOrFail($id);

        // 画像がある場合は削除
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // 中間テーブルの関連を外す
        $product->seasons()->detach();

        // 商品削除
        $product->delete();

        return response()->json([
            'message' => '商品を削除しました'
        ], 200);
    }
}
