@extends('layouts.app')

@section('content')
@section('body-class', 'narrow-page') {{-- 標準サイズ --}}
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

    {{-- ボタン全体のレイアウト --}}
    <div class="d-flex justify-content-center gap-3 mt-4">

        {{-- 左側：中央寄せ用のボタン群 --}}
        <div class="d-flex justify-content-center w-100 gap-3">
            <a href="{{ url('/products') }}" class="btn btn-secondary">← 戻る</a>
            <button type="submit" class="btn btn-warning">変更を保存</button>
        </div>
</form>

{{-- 右側：削除ボタン --}}
<form method="POST"
    action="{{ route('products.destroy', $product->id) }}"
    onsubmit="alert('確認ダイアログのテスト'); return false;" class="ms-auto">
    @csrf
    @method('DELETE')
    <!-- メソッドスプーフィング追加-->
    <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">
        🗑 削除
    </button>
</form>

</div>



@endsection
