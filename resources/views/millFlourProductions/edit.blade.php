@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">製粉生産編集</h1>
    <form action="{{ route('millFlourProductions.update', $millFlourProduction->id) }}" method="POST" class="lg:flex lg:gap-10">
        @csrf
        @method('PUT')
        <!-- 左側のセクション -->
        <div class="lg:w-2/5">
            <!-- 製粉日 -->
            <div class="mb-4">
                <label for="production_date" class="block text-sm font-bold mb-2">製粉日</label>
                <input type="date" id="production_date" name="production_date" value="{{ $millFlourProduction->production_date->format('Y-m-d') }}" class="input input-bordered w-full bg-base-200" required readonly>
            </div>
    
            <!-- 製粉機の選択 -->
            <div class="mb-4">
                <label for="mill_machine_id" class="block text-sm font-bold mb-2">製粉機</label>
                <div class="w-full">No.{{ $millFlourProduction->millMachine->machine_number}}-{{ $millFlourProduction->millMachine->machine_name }}</div>
            </div>
            
            <!-- 開始時間 -->
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-bold mb-2">開始時間</label>
                <input type="time" id="start_time" name="start_time" value="{{ $millFlourProduction->start_time }}" class="input input-bordered w-full">
            </div>
            
            <!-- 終了時間 -->
            <div class="mb-4">
                <label for="end_time" class="block text-sm font-bold mb-2">終了時間</label>
                <input type="time" id="end_time" name="end_time" value="{{ $millFlourProduction->end_time }}" class="input input-bordered w-full">
            </div>
            <!-- 製品小麦粉量 -->
            <div class="mb-4">
                <label for="flour_weight" class="block text-sm font-bold mb-2">製品小麦粉量（kg）</label>
                <input type="number" id="flour_weight" name="flour_weight" value="{{ $millFlourProduction->flour_weight }}" step="0.01" class="input input-bordered w-full">
            </div>
            <!-- 製品ふすま量 -->
            <div class="mb-4">
                <label for="bran_weight" class="block text-sm font-bold mb-2">製品ふすま量（kg）</label>
                <input type="number" id="bran_weight" name="bran_weight" value="{{ $millFlourProduction->bran_weight }}" step="0.01" class="input input-bordered w-full">
            </div>
            
            <!-- 備考 -->
            <div class="mb-4">
                <label for="remarks" class="block text-sm font-bold mb-2">備考</label>
                <textarea class="textarea textarea-bordered w-full" id="remarks" name="remarks" rows="3">{{ $millFlourProduction->remarks }}</textarea>
            </div>

        </div>
        <!-- 右側のセクション -->
        <div class="lg:w-3/5">
            <div id="DetailsContainer" class="mb-4">
                <label class="block text-xm font-bold mb-2" for="polished_date">
                    使用精麦原料
                </label>
                @foreach ($millFlourProduction->millPolishedMaterials as $material)
                <div class="flex items-center gap-2 mb-4">
                    <select class="select select-bordered flex-1" name="mill_polished_material_ids[]">
                        <option value="{{ $material->id }}" selected>
                            {{ $material->polished_lot_number }}
                            @if ($material->millPurchaseMaterials->first() && $material->millPurchaseMaterials->first()->material) - {{ $material->millPurchaseMaterials->first()->material->materials_name }}</option>
                            @endif
                    </select>
                <input class="input input-bordered flex-1" value="{{ $material->pivot->input_weight }}" name="input_weights[]" type="number" step="0.01" placeholder="投入量(kg)" required>
                <span>残:{{ $material->remaining_polished_amount }}kg</span>
                <button type="button" class="removeDetail btn btn-error" data-materialid="{{ $material->id }}">削除</button>
                </div>
                @endforeach
            </div>
            <button id="addDetails" type="button" class="btn btn-primary mb-4">原料を追加</button>
            <button class="btn btn-success" type="submit">登録</button>
        </div>
    </form>
    <!-- 削除ボタン -->
        <form action="{{ route('millFlourProductions.destroy', $millFlourProduction->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error mt-4">削除</button>
        </form>
</div>
@include('commons.detailInputs')
@endsection