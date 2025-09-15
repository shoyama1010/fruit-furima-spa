@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endpush

@section('body-class', 'wide-page')

@section('content')
<!-- 商品一覧 + 商品登録ボタン -->
<div class="page-header">
    <h2>商品一覧</h2>
    <a href="{{ route('products.create') }}" class="register-button">＋ 商品を登録</a>
</div>

<div class="product-container">
    <!-- 左カラム：検索とソート -->
    <div class="sidebar">
        <form method="GET" action="{{ route('products.search') }}" class="search-form">
            @csrf

            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
            <button type="submit" class="search-button">検索</button>

            <label for="sort">価格で並べ替え:</label>

            <select name="sort" onchange="this.form.submit()">
                <option value="">選択してください</option>
                <option value="high" {{ request('sort') === 'high' ? 'selected' : '' }}>高い順に表示</option>
                <option value="low" {{ request('sort') === 'low' ? 'selected' : '' }}>安い順に表示</option>
            </select>
        </form>
    </div>

    <!-- 右カラム：商品一覧 -->
    <div class="product-list">

        @foreach ($products as $product)
        <a href="{{ route('products.edit',$product->id) }}" class="product-card">
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">

            <h3>{{ $product->name }}</h3>
            <p class="card-price">¥{{ $product->price }}</p>
        </a>
        @endforeach

        <!-- ページネーション -->
        <div class="pagination">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection