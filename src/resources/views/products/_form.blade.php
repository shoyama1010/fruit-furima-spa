
@csrf

<div class="mb-3">
    <label class="form-label">商品名</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">価格</label>
    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">画像</label>
    <input type="file" name="image" class="form-control">
    @if (isset($product) && $product->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $product->image) }}" width="120">
        </div>
    @endif
</div>

<div class="mb-3">
    <label class="form-label">商品説明</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">季節カテゴリ</label><br>
    @foreach ($seasons as $season)
        <label class="me-3">
            <input type="checkbox" name="seasons[]" value="{{ $season->id }}"
                {{ in_array($season->id, old('seasons', isset($product) ? $product->seasons->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
            {{ $season->name }}
        </label>
    @endforeach
</div>

<button type="submit" class="btn btn-primary">保存</button>
