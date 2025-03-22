@extends('layouts.app')

@section('content')
<h1>新規登録</h1>
<form method="POST" action="{{ url('/products/register') }}" enctype="multipart/form-data">
    
    @include('products._form')
</form>
<a class="btn btn-link mt-3" href="{{ url('/products') }}">← 戻る</a>
@endsection
