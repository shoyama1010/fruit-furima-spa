@extends('layouts.app')

@section('content')
<h1>{{ $product->name }}</h1>
<img src="{{ asset('storage/' . $product->image) }}" width="300" class="img-fluid mb-3">
<p><strong>価格：</strong>¥{{ $product->price }}</p>
<p><strong>説明：</strong>{{ $product->description }}</p>
<p><strong>季節：</strong>{{ $product->seasons->pluck('name')->join(', ') }}</p>
<a class="btn btn-secondary mt-3" href="{{ url('/products') }}">← 商品一覧に戻る</a>
@endsection
