@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4"><!-- 戻るボタン -->
        <a href="{{ route('supplyItems.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
        <h1 class="text-2xl font-semibold">新規資材備品登録</h1>
        <div></div>
    </div>
    <div class="w-full mx-auto ">
        <form action="{{ route('supplyItems.store') }}" id="uploadForm" method="POST" enctype="multipart/form-data">
            <div class="lg:flex lg:gap-10 text-left">
                @csrf
                <!-- 左側のセクション -->
                <div class="lg:w-1/2">
                    
                    
                    <div class="form-group mb-4">
                        <label for="thumbnail" class="form-label">資材備品画像（.jpg/.jpeg/.pngのみ）</label>
                        <input type="file" id="thumbnail" name="thumbnail" class="file-input file-input-bordered file-input-sm w-full" accept="image/jpeg,image/jpg,image/png">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="item_code" class="form-label">資材コード１３桁(JANコードor自社コード)</label>
                        <div class="flex items-center">
                            <input type="number" id="item_code" name="item_code" class="input input-bordered w-full" pattern="\d{13}" placeholder="1234567890123" oninput="updateLengthDisplay()">
                            <span id="checkmark" style="display:none; margin-left: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <small>現在の入力桁数: <span id="input_length">0</span>桁</small>
                    </div>
                    <script>
                        // 入力桁数チェック
                        function updateLengthDisplay() {
                            var input = document.getElementById('item_code');
                            var inputLength = input.value.length;
                            var checkmark = document.getElementById('checkmark');
                        
                            document.getElementById('input_length').textContent = inputLength;
                        
                            // 数字以外の文字を削除
                            input.value = input.value.replace(/[^\d]/g, '');
                            
                            // 入力桁数が13桁の場合にチェックマークを表示
                            if(inputLength === 13) {
                                checkmark.style.display = "inline";
                            } else {
                                checkmark.style.display = "none";
                            }
                        }
                    </script>
        
                    <div class="form-group mb-4">
                        <label for="item_name" class="form-label">備品名</label>
                        <input type="text" id="item_name" name="item_name" class="input input-bordered w-full" placeholder="ベーカリスタ CH袋 2.5kg ロゴ無し" required>
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="standard" class="form-label">規格</label>
                        <input type="text" id="standard" name="standard" class="input input-bordered w-full" placeholder="280×410+27">
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="brand_name" class="form-label">ブランド名（メーカー名）</label>
                        <input type="text" id="brand_name" name="brand_name" class="input input-bordered w-full" placeholder="TP東京">
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="category" class="form-label">分類</label>
                        <select id="category" name="category" class="select select-bordered w-full">
                            <option value="">選択してください</option>
                            <option value="製品資材">製品資材</option>
                            <option value="物流資材">物流資材</option>
                            <option value="業務用消耗品">業務用消耗品</option>
                            <option value="日用消耗品">日用消耗品</option>
                        </select>
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="price" class="form-label">１個単価(税抜)</label>
                        <input type="number" id="price" name="price" step="0.01" class="input input-bordered w-full" placeholder="36.5">
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="order_lot" class="form-label">発注ロット（個数）</label>
                        <input type="number" id="order_lot" name="order_lot" class="input input-bordered w-full" placeholder="12000">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="description" class="form-label">備考</label>
                        <textarea id="description" name="description" class="textarea textarea-bordered w-full"></textarea>
                    </div>
                    
                </div>
                <!-- 右側のセクション（使用原料登録など） -->
                <div class="lg:w-1/2">
        
                    <div class="form-group mb-4">
                        <label for="files" class="form-label">資材資料（見積もり/規格書/提案書等）最大１０個</label>
                        @for ($i = 0; $i < 10; $i++)
                            <div class="mb-2">
                                <input type="file" id="files{{ $i }}" name="files[]" class="file-input file-input-bordered file-input-sm w-full">
                            </div>
                        @endfor
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="print_image" class="form-label">印刷用データ（.ai /.pdf 合計２個）</label>
                        @for ($i = 0; $i < 2; $i++)
                            <div class="mb-2">
                                <input type="file" id="print_images{{ $i }}" name="print_images[]" class="file-input file-input-bordered file-input-sm w-full" accept=".ai,application/pdf">
                            </div>
                        @endfor
                    </div>

        
                    <div class="form-group mb-4">
                        <label for="order_point" class="form-label">発注点（個数）</label>
                        <input type="number" id="order_point" name="order_point" class="input input-bordered w-full" placeholder="在庫数が発注点を下回ると発注対象になります。">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="constant_stock" class="form-label">在庫定数</label>
                        <input type="number" id="constant_stock" name="constant_stock" class="input input-bordered w-full" placeholder="在庫定数を満たすロット数が発注されます。" min="0" max="100000" step="1">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="actual_stock" class="form-label">実在庫</label>
                        <input type="number" id="actual_stock" name="actual_stock" class="input input-bordered w-full" placeholder="すでに実在庫があれば入力します。" min="0" max="100000" step="1">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="order_url" class="form-label">発注URL</label>
                        <input type="url" id="order_url" name="order_url" class="input input-bordered w-full" placeholder="http://example.com">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="order_schedule" class="form-label">次回発注予定日</label>
                        <input type="date" id="order_schedule" name="order_schedule" class="input input-bordered w-full">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="delivery_period" class="form-label">通常納期（日数）</label>
                        <input type="number" id="delivery_period" name="delivery_period" class="input input-bordered w-full" placeholder="例：3（３日）、14（2週間）、30（1ヶ月）" min="1" max="100">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="location_code" class="form-label">保管場所</label>
                        <select id="location_code" name="location_code" class="select select-bordered w-full">
                                <option disabled selected>保管場所を選択してください</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_code }}-{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
        
                    <div class="form-group mb-4">
                        <label for="company_id" class="form-label">発注先</label>
                        <select id="company_id" name="company_id" class="select select-bordered w-full">
                                <option disabled selected>発注先を選択してください</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
    
            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
