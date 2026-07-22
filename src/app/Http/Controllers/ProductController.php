<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private const SEED_IMAGE_FILENAMES = [
        'banana.png',
        'grapes.png',
        'kiwi.png',
        'melon.png',
        'muscat.png',
        'orange.png',
        'peach.png',
        'pineapple.png',
        'strawberry.png',
        'watermelon.png',
    ];

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
            'user_id' => $request->user()->id, // ← 追加
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
            // 新しい画像を保存する前に、古い投稿画像だけ削除
            $this->deleteUploadedImage($product->image);

            $product->image = $request
                ->file('image')
                ->store('products', 'public');
        }

        $product->fill(
            $request->only([
                'name',
                'price',
                'description',
            ])
        );

        $product->save();

        $product->seasons()->sync(
            $request->input('seasons', [])
        );

        return redirect('/products')
            ->with('success', '商品を更新しました。');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // ユーザー投稿画像だけ削除する
        $this->deleteUploadedImage($product->image);

        // 中間テーブルの関連を解除
        $product->seasons()->detach();

        $product->delete();

        return redirect('/products')
            ->with('success', '商品を削除しました。');
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

        // $products = $query->get();

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
        $product->user_id = $request->user()->id; // ログインユーザーに紐づける
        // $product->user_id = 1; // ★仮で固定（ログイン不要にする）

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

        // 所有者チェック
        if ((int) $product->user_id !== (int) $request->user()->id) {
            return response()->json([
                'message' => '権限がありません',
            ], 403);
        }

        if ($request->hasFile('image')) {
            // 古い投稿画像だけ削除
            $this->deleteUploadedImage($product->image);

            // 新しい画像を保存
            $product->image = $request
                ->file('image')
                ->store('products', 'public');
        }

        $product->fill(
            $request->only([
                'name',
                'price',
                'description',
            ])
        );

        $product->save();

        $product->seasons()->sync(
            $request->input('seasons', [])
        );

        return response()->json([
            'message' => '商品を更新しました',
            'product' => $product->load('seasons'),
        ], 200);
    }


    public function apiDestroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // 自分の商品以外は削除不可
        if ((int) $product->user_id !== (int) $request->user()->id) {
            return response()->json([
                'message' => '権限がありません',
            ], 403);
        }

        // ユーザー投稿画像だけ削除
        $this->deleteUploadedImage($product->image);

        // 中間テーブルの関連を解除
        $product->seasons()->detach();

        $product->delete();

        return response()->json([
            'message' => '商品を削除しました',
        ], 200);
    }


    public function myProducts(Request $request)
    {
        $products = Product::with('seasons')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json($products);
    }

    /**
     * ユーザーがアップロードした商品画像だけ削除する
     */
    private function deleteUploadedImage(?string $imagePath): void
    {
        if (!$imagePath) {
            return;
        }

        // public/images 配下のSeeder固定画像は削除しない
        if (str_starts_with($imagePath, 'images/products/')) {
            return;
        }

        // 現在DBに残っている旧形式のSeeder固定画像も削除しない
        if (
            str_starts_with($imagePath, 'products/') &&
            in_array(
                basename($imagePath),
                self::SEED_IMAGE_FILENAMES,
                true
            )
        ) {
            return;
        }

        // storage/app/public/products に保存された投稿画像だけ削除
        if (
            str_starts_with($imagePath, 'products/') &&
            Storage::disk('public')->exists($imagePath)
        ) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
