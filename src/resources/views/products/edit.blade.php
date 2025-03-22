@extends('layouts.app')

@section('content')
<h1>編集：{{ $product->name }}</h1>
<form method="POST" action="{{ url('/products/' . $product->id . '/update') }}" enctype="multipart/form-data">
    @method('POST')
    
    @include('products._form')
</form>
<a class="btn btn-link mt-3" href="{{ url('/products') }}">← 戻る</a>
@endsection
