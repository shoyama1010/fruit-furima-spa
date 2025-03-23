@extends('layouts.app')

@section('content')
<h1>新規登録</h1>
<h2 class="mb-4 text-center">商品登録</h2>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="mx-auto" style="max-width: 600px;">
    @csrf
    
    {{-- 商品名 --}}
    <div class="mb-3">
        <label for="name" class="form-label">商品名 <span class="text-danger">※</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- 商品価格 --}}
    <div class="mb-3">
        <label for="price" class="form-label">価格 <span class="text-danger">※</span></label>
        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}">
        @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- 画像 --}}
    <div class="mb-3">
        <label for="image" class="form-label">商品画像 <span class="text-danger">※</span></label>
        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image">
        @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- 季節カテゴリ --}}
    <div class="mb-3">
        <label class="form-label d-block">季節 <span class="text-danger">※</span></label>
        @foreach ($seasons as $season)
        <div class="form-check form-check-inline">
            <input class="form-check-input @error('seasons') is-invalid @enderror" type="checkbox" name="seasons[]" value="{{ $season->id }}" {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
            <label class="form-check-label">{{ $season->name }}</label>
        </div>
        @endforeach
        @error('seasons')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
    {{-- 商品説明 --}}
    <div class="mb-4">
        <label for="description" class="form-label">商品説明 <span class="text-danger">※</span></label>
        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    {{-- ボタン --}}
    <div class="d-flex justify-content-center gap-4">
        <a href="{{ url('/products') }}" class="btn btn-secondary px-5">戻る</a>
        <button type="submit" class="btn btn-warning px-5">登録</button>
    </div>
</form>

@endsection
