@extends('layouts.app')

@section('content')
<h1 class="mb-4">商品一覧</h1>
<form method="GET" action="{{ url('/products/search') }}" class="row g-3 mb-4">
    @csrf
    <div class="col-auto">
        <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="商品名で検索">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary" type="submit">検索</button>
    </div>
    <div class="col-auto">
        <!-- <a href="{{ url('/products/create') }}" class="btn btn-warning">＋ 商品を登録</a> -->
        <a href="{{ route('products.create') }}" class="btn btn-warning">＋ 商品を登録</a>
    </div>
</form>
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
@endsection
