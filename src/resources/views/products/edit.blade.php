


@extends('layouts.app')

@section('content')
<p class="mb-4">商品一覧>{{ $product->name }}</p>

<form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4 align-items-start">
        <!-- 左：画像 -->
        <div class="col-md-4">
            <label for="image" class="form-label">商品画像</label>
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-2" style="max-height: 200px; object-fit: cover;">
            <input type="file" name="image" class="form-control">
        </div>

        <!-- 右：名前、価格、季節 -->
        <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">商品名</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">価格</label>
                <input type="number" name="price" class="form-control" value="{{ $product->price }}">
            </div>
            <div class="mb-3">
                <label class="form-label">季節</label><br>
                @foreach ($seasons as $season)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="seasons[]" value="{{ $season->id }}" class="form-check-input"
                               {{ $product->seasons->contains($season->id) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $season->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 説明 -->
    <div class="mb-4 mt-4">
        <label for="description" class="form-label">商品説明</label>
        <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
    </div>

    <!-- ボタン -->
    <div class="d-flex justify-content-between">
        <a href="{{ url('/products') }}" class="btn btn-secondary">戻る</a>
        <button type="submit" class="btn btn-warning">変更を保存</button>
    </div>
</form>


@endsection
