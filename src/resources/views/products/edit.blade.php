@extends('layouts.app')

@section('content')
<h1 class="mb-4">編集：{{ $product->name }}</h1>

<form method="POST" action="{{ url('/products/' . $product->id . '/update') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')

    {{-- 商品名 --}}
    <div class="mb-3">
        <label class="form-label">商品名</label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror">
        @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- 金額 --}}
    <div class="mb-3">
        <label class="form-label">価格</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control @error('price') is-invalid @enderror">
        @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- 季節カテゴリ --}}
    <div class="mb-3">
        <label class="form-label">季節</label>
        @foreach ($seasons as $season)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                    class="form-check-input"
                    {{ in_array($season->id, old('seasons', $product->seasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $season->name }}</label>
            </div>
        @endforeach
        @error('seasons')
        <div class="text-danger d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- 画像 --}}
    <div class="mb-3">
        <label class="form-label">商品画像</label>
        @if ($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" alt="商品画像" class="img-thumbnail mt-2" width="200">
        @endif
        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

        @error('image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    {{-- 商品説明 --}}
    <div class="mb-3">
        <label class="form-label">商品説明</label>
        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
        @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- ボタン --}}
    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="{{ url('/products') }}" class="btn btn-secondary px-5">戻る</a>
        <button type="submit" class="btn btn-warning px-5">変更を保存</button>
    </div>
</form>

@endsection
