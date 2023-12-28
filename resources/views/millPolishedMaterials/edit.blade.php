@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">精麦済み原料の編集</h1>
        <form action="{{ route('millPolishedMaterials.update', $polishedMaterial->id) }}" method="POST" id="editForm" class="lg:flex lg:gap-10">
            @csrf
            @method('PUT') 
            <!-- 左側のセクション -->
            <div class="lg:w-1/4">
                <!-- 精麦日付（表示のみ） -->
                <div class="mb-4">
                    <label for="polished_date" class="block text-sm font-bold mb-2">精麦日付（変更不可）:</label>
                    <input type="text" name="polished_date" class="input input-bordered w-full" value="{{ $polishedMaterial->polished_date }}" disabled>
                </div>
    
                <!-- 総重量の編集 -->
                <div class="mb-4">
                    <label for="total_output_weight" class="block text-sm font-bold mb-2">総重量(kg):</label>
                    <input type="number" name="total_output_weight" class="input input-bordered w-full" value="{{ $polishedMaterial->total_output_weight }}">
                </div>
            </div>
            
            <!--右側のセクション-->
            <div class="lg:w-3/4">
                <!-- 使用原料の編集 -->
                <div class="mb-4" id="materialsContainer">
                    <label for="materials" class="block text-sm font-bold mb-2">使用原料:</label>
                    <!-- 既存の原料を表示・編集 -->
                    @foreach ($polishedMaterial->millPurchaseMaterials as $material)
                    <div class="flex items-center gap-2 mb-4">
                        <select class="select select-bordered flex-1" name="selectMaterials[]">
                            <option value="{{ $material->id }}" selected>{{ $material->lot_number }} - {{ $material->material->materials_name }}</option>
                            <!-- 他の選択可能な原料をここにリストする -->
                        </select>
                        <input class="input input-bordered flex-1" name="input_weights[]" type="number" value="{{ $material->pivot->input_weight }}" step="0.01">
                        <span>残:{{ $material->remaining_amount }} kg</span> <!-- 在庫残数を表示する -->
                        <button type="button" class="removeMaterial btn btn-error" data-materialid="{{ $material->id }}">削除</button>
                    </div>
                    @endforeach
                </div>
                <!-- 新規原料追加ボタン -->
                <button id="addMaterial" type="button" class="btn btn-primary mb-4">原料を追加</button>
            <!-- 更新ボタン -->
                <button type="submit" class="btn btn-primary">更新</button>
            </div>
        </form>

        <!-- 削除ボタン -->
        <form action="{{ route('millPolishedMaterials.destroy', $polishedMaterial->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error mt-4">削除</button>
        </form>
</div>

@include('commons.materialInputs')
@endsection
