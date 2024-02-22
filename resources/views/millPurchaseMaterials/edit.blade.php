@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('millPurchaseMaterials.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
    
        <h1 class="text-2xl font-bold">原料入荷編集</h1>
    
        <!-- 新規作成ボタン -->
        <a href="{{ route('millPurchaseMaterials.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('millPurchaseMaterials.update', $millPurchaseMaterial->id) }}" id="uploadForm" class="mb-4" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-control">
                <label class="label" for="materials_id">
                    <span class="label-text">原材料<span class="text-info">（変更不可）</span></span>
                </label>
                <!-- material_idはreadonly属性で編集不可 -->
                <input type="text" id="materials_id" name="materials_id" value="{{ $millPurchaseMaterial->material->materials_code }} - {{ $millPurchaseMaterial->material->materials_name }}" class="input input-bordered bg-base-200" readonly>
            </div>

            <div class="form-control">
                <label class="label" for="arrival_date">
                    <span class="label-text">入荷日付</span>
                </label>
                
                <input type="text" id="datePicker" name="arrival_date" value="{{ $millPurchaseMaterial->arrival_date->format('Y年m月d日') }}" class="input input-bordered">
            </div>
            
            <div class="form-control">
                <label class="label" for="year_of_production">
                    <span class="label-text">生産年度<span class="text-info">（変更不可）</span></span>
                </label>
                <input type="text" id="year_of_production" name="year_of_production" value="{{ $millPurchaseMaterial->year_of_production }}" class="input input-bordered bg-base-200" required readonly>
            </div>

            <div class="form-control">
                <label class="label" for="flecon_number">
                    <span class="label-text">フレコン番号<span class="text-info">（変更不可）</span></span>
                </label>
                <input type="text" id="flecon_number" name="flecon_number" value="{{ $millPurchaseMaterial->flecon_number }}" class="input input-bordered bg-base-200" required readonly>
            </div>

            <div class="form-control">
                <label class="label" for="total_amount">
                    <span class="label-text">入荷量（kg）在庫数は自動再計算されます。</span>
                </label>
                <input type="number" id="total_amount" name="total_amount" value="{{ $millPurchaseMaterial->total_amount }}" class="input input-bordered">
            </div>

            <div class="form-control">
                <label class="label" for="lot_number">
                    <span class="label-text">ロットナンバー<span class="text-info">（変更不可）</span></span>
                </label>
                <!-- lot_numberはreadonly属性で編集不可 -->
                <input type="text" id="lot_number" name="lot_number" value="{{ $millPurchaseMaterial->lot_number }}" class="input input-bordered bg-base-200" readonly>
            </div>

            <div class="form-control">
                <label class="label" for="cost">
                    <span class="label-text">仕入れ価格（総額）</span>
                </label>
                <input type="number" id="cost" name="cost" value="{{ $millPurchaseMaterial->cost }}" class="input input-bordered">
            </div>
            <div class="form-control">
                <label class="label" for="inspection_completed">
                    <span class="label-text">入荷検品</span>
                    <input type="checkbox" id="inspection_completed" name="inspection_completed" value="1" class="checkbox" {{ $millPurchaseMaterial->inspection_completed == 1 ? 'checked' : '' }}>
                </label>
            </div>

            
            <div class="flex mt-6">
                <div class="form-control w-1/2 mr-3">
                    <button type="submit" class="btn btn-secondary">更新</button>
                </div>
                </form>
                <form action="{{ route('millPurchaseMaterials.destroy', $millPurchaseMaterial->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning w-full">入荷情報を削除</button>
                </form>
            </div>
        </div>
    </div>
@endsection
