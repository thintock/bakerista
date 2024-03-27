@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('productItems.index') }}" class="btn btn-secondary">
            ← 商品一覧へ
        </a>
    
        <h1 class="text-2xl font-bold">商品情報編集</h1>
    
        <!-- 新規作成ボタン -->
        <a href="{{ route('productItems.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
    <form action="{{ route('productItems.update', $productItem->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        
        <div>
            <label for="item_code" class="block mb-2">商品コード</label>
            <input type="text" id="item_code" name="item_code" value="{{ $productItem->item_code }}" class="input input-bordered w-full" required>
        </div>

        <div>
            <label for="jan_code" class="block mb-2">JANコード</label>
            <input type="text" id="jan_code" name="jan_code" value="{{ $productItem->jan_code }}" class="input input-bordered w-full">
        </div>

        <div>
            <label for="name" class="block mb-2">商品名</label>
            <input type="text" id="name" name="name" value="{{ $productItem->name }}" class="input input-bordered w-full" required>
        </div>

        <div>
            <label for="brand_name" class="block mb-2">ブランド名</label>
            <input type="text" id="brand_name" name="brand_name" value="{{ $productItem->brand_name }}" class="input input-bordered w-full">
        </div>

        <div>
            <label for="description" class="block mb-2">備考</label>
            <textarea id="description" name="description" class="textarea textarea-bordered w-full">{{ $productItem->description }}</textarea>
        </div>

        <div>
            <label for="item_status" class="block mb-2">商品ステータス</label>
            <input type="text" id="item_status" name="item_status" value="{{ $productItem->item_status }}" class="input input-bordered w-full" required>
        </div>
        {{-- ラベル用商品名 --}}
        <div>
            <label for="label_name" class="block mb-2">ラベル用商品名</label>
            <input type="text" id="label_name" name="label_name" value="{{ $productItem->label_name }}" class="input input-bordered w-full">
        </div>
        {{-- ラベル用商品名 --}}
        <div>
            <label for="label_kana" class="block mb-2">ラベル用商品名(よみがな)</label>
            <input type="text" id="label_kana" name="label_kana" value="{{ $productItem->label_kana }}" class="input input-bordered w-full">
        </div>
        
        {{-- ラベル用サブ商品名 --}}
        <div>
            <label for="label_sub_name" class="block mb-2">ラベル用サブ商品名</label>
            <input type="text" id="label_sub_name" name="label_sub_name" value="{{ $productItem->label_sub_name }}" class="input input-bordered w-full">
        </div>
        
        {{-- ラベル用規格 --}}
        <div>
            <label for="label_standard" class="block mb-2">ラベル用規格</label>
            <input type="text" id="label_standard" name="label_standard" value="{{ $productItem->label_standard }}" class="input input-bordered w-full">
        </div>
        
        {{-- ラベル用商品説明 --}}
        <div>
            <label for="label_description" class="block mb-2">ラベル用商品説明</label>
            <textarea id="label_description" name="label_description" class="textarea textarea-bordered w-full">{{ $productItem->label_description }}</textarea>
        </div>
        
        {{-- 食品内容表示：名称 --}}
        <div>
            <label for="food_content_name" class="block mb-2">食品内容表示：名称</label>
            <input type="text" id="food_content_name" name="food_content_name" value="{{ $productItem->food_content_name }}" class="input input-bordered w-full">
        </div>
        
        {{-- 食品内容表示：原材料名 --}}
        <div>
            <label for="food_content_ingredients" class="block mb-2">食品内容表示：原材料名</label>
            <input type="text" id="food_content_ingredients" name="food_content_ingredients" value="{{ $productItem->food_content_ingredients }}" class="input input-bordered w-full">
        </div>
        
        {{-- 食品内容表示：内容量 --}}
        <div>
            <label for="food_content_volume" class="block mb-2">食品内容表示：内容量</label>
            <input type="text" id="food_content_volume" name="food_content_volume" value="{{ $productItem->food_content_volume }}" class="input input-bordered w-full">
        </div>
        
        <div>
            <label for="shelf_life" class="block mb-2">食品内容表示：賞味期限</label>
            <input type="text" id="shelf_life" name="shelf_life" value="{{ $productItem->shelf_life }}" class="input input-bordered w-full">
        </div>
        
        {{-- 食品内容表示：保存方法 --}}
        <div>
            <label for="storage_method" class="block mb-2">食品内容表示：保存方法</label>
            <input type="text" id="storage_method" name="storage_method" value="{{ $productItem->storage_method }}" class="input input-bordered w-full">
        </div>
        
        {{-- アレルギー表示 --}}
        <div>
            <label for="allergen_display" class="block mb-2">アレルギー表示</label>
            <input type="text" id="allergen_display" name="allergen_display" value="{{ $productItem->allergen_display }}" class="input input-bordered w-full">
        </div>
        
        {{-- 栄養成分表示 --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="nutritional_energy" class="block mb-2">エネルギー</label>
                <input type="text" id="nutritional_energy" name="nutritional_energy" value="{{ $productItem->nutritional_energy }}" class="input input-bordered w-full">
            </div>
            <div>
                <label for="nutritional_protein" class="block mb-2">たんぱく質</label>
                <input type="text" id="nutritional_protein" name="nutritional_protein" value="{{ $productItem->nutritional_protein }}" class="input input-bordered w-full">
            </div>
            <div>
                <label for="nutritional_fat" class="block mb-2">脂質</label>
                <input type="text" id="nutritional_fat" name="nutritional_fat" value="{{ $productItem->nutritional_fat }}" class="input input-bordered w-full">
            </div>
            <div>
                <label for="nutritional_carbohydrate" class="block mb-2">炭水化物</label>
                <input type="text" id="nutritional_carbohydrate" name="nutritional_carbohydrate" value="{{ $productItem->nutritional_carbohydrate }}" class="input input-bordered w-full">
            </div>
            <div>
                <label for="nutritional_salt_equivalent" class="block mb-2">食塩相当量</label>
                <input type="text" id="nutritional_salt_equivalent" name="nutritional_salt_equivalent" value="{{ $productItem->nutritional_salt_equivalent }}" class="input input-bordered w-full">
            </div>
            <div>
                <label for="nutritional_ash" class="block mb-2">灰分</label>
                <input type="text" id="nutritional_ash" name="nutritional_ash" value="{{ $productItem->nutritional_ash }}" class="input input-bordered w-full">
            </div>
        </div>
        <div>
            <label for="manufacturer_id" class="block mb-2">製造者</label>
            <select id="manufacturer_id" name="manufacturer_id" class="select select-bordered w-full">
                    <option value="">選択してください</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" @if($company->id == $productItem->manufacturer_id) selected @endif>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- ここに他のフィールドを追加します --}}
        
        <div class="flex justify mt-4">
            <button type="submit" class="btn btn-primary w-1/2 mr-2">更新</button>
        </form>
        <form action="{{ route('productItems.destroy', $productItem->id) }}" class="w-1/2" method="POST" onsubmit="return confirm('関連するラベル印刷履歴も全て削除されますが、実行しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-warning w-full">削除</button>
        </form>
    </div>
</div>
@endsection
