@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4"><!-- 戻るボタン -->
        <a href="{{ route('productItems.index') }}" class="btn btn-secondary">
            ← 商品一覧へ
        </a>
        <h1 class="text-2xl font-semibold">新規商品追加</h1></h1>
        <div></div>
    </div>
    <form action="{{ route('productItems.store') }}" method="POST" class="space-y-4">
        @csrf
        
        <div>
            <label for="item_code" class="block mb-2">商品コード</label>
            <input type="text" id="item_code" name="item_code" class="input input-bordered w-full" required>
        </div>

        <div>
            <label for="name" class="block mb-2">商品名</label>
            <input type="text" id="name" name="name" class="input input-bordered w-full" required>
        </div>

        <div>
            <label for="brand_name" class="block mb-2">ブランド名</label>
            <input type="text" id="brand_name" name="brand_name" class="input input-bordered w-full">
        </div>

        <div>
            <label for="description" class="block mb-2">備考</label>
            <textarea id="description" name="description" class="textarea textarea-bordered w-full"></textarea>
        </div>
        
        <div class="flex justify-end mt-4">
            <button type="submit" class="btn btn-primary w-full">商品登録</button>
        </div>
    </form>
</div>
@endsection
