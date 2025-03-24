@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endpush

@section('content')
<div class="container">
    <!-- <div class="products-page-container"> -->
    <div class="row">
        <!-- 左カラム：検索フォーム -->
        <!-- <div class="search-sidebar"> -->
        <div class="col-md-3 mb-4">
            <!-- <div class="col-md-3"> -->
            <form method="GET" action="{{ url('/products') }}">
                <!-- <h2 class="mb-3">商品一覧</h2> -->
                <h2> 商品一覧</h2>
                <div class="mb-3">
                    <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary" type="submit">検索</button>
                </div>

                <!-- ソートボックス -->
                <div class="mb-4">
                    <!-- <div class="mb-3"> -->
                    <label for="sort" class="form-label">価格で並べ替え:</label>
                    <select class="form-select" name="sort" onchange="this.form.submit()">
                        <option value="">選択してください</option>
                        <option value="high" {{ request('sort') === 'high' ? 'selected' : '' }}>金額高い順</option>
                        <option value="low" {{ request('sort') === 'low' ? 'selected' : '' }}>金額低い順</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- 右カラム：商品一覧と登録ボタン -->
        <!-- <div class="product-list-area"> -->
        <div class="col-md-9">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('products.create') }}" class="btn btn-warning">＋ 商品を登録</a>
            </div>

            <!-- 商品カード一覧 -->
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($products as $product)
                <div class="col">
                    <a href="{{ url('/products/' . $product->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">¥{{ $product->price }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
