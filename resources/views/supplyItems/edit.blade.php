@extends('layouts.app')

@section('content')
<div class="container">
    <div class="flex justify-between items-center mb-4">
        <!-- 戻るボタン -->
        <a href="{{ route('supplyItems.index') }}" class="btn btn-secondary">
            ← 戻る
        </a>
    
        <h1 class="text-2xl font-bold">資材備品情報編集</h1>
    
        <!-- 新規作成ボタン -->
        <a href="{{ route('supplyItems.create') }}" class="btn btn-primary">
            新規作成
        </a>
    </div>
    <div class="w-full mx-auto">
        <form action="{{ route('supplyItems.update', $supplyItem->id) }}" id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="lg:flex lg:gap-10 text-left">
                <!-- 左側のセクション -->
                <div class="lg:w-1/2">
                    
                    <div class="form-group mb-4">
                        <label for="item_name" class="form-label mr-3">ステータス</label>
                        <select name="item_status" class="select select-bordered w-full max-w-xs">
                            <option value="未承認" {{ $supplyItem->item_status === '未承認' ? 'selected' : '' }}>未承認</option>
                            <option value="承認申請中" {{ $supplyItem->item_status === '承認申請中' ? 'selected' : '' }}>承認申請中</option>
                            <option value="承認済み" {{ $supplyItem->item_status === '承認済み' ? 'selected' : '' }}>承認済み</option>
                            <option value="使用終了" {{ $supplyItem->item_status === '使用終了' ? 'selected' : '' }}>使用終了</option>
                        </select>
                    </div>
                    
                    <div class="form-group mb-4">
                        @if($supplyItem->thumbnail)
                            <div class="flex justify-between items-center">
                                <img src="{{ Storage::url($supplyItem->thumbnail) }}" alt="サムネイル" class="top-0 left-0 w-5/6 aspect-square object-cover rounded-lg cursor-pointer" onclick="showModal('{{ Storage::url($supplyItem->thumbnail) }}')">
                                <label class="flex items-center justify-center w-1/6">
                                    <div class="btn btn-xs btn-warning"><input type="checkbox" name="delete_thumbnail" class="checkbox checkbox-warning" style="width: 1rem; height: 1rem;">削除</div>
                                </label>
                            </div>
                        @else
                            <label for="thumbnail" class="form-label">資材備品画像（.jpg/.jpeg/.pngのみ）</label>
                            <input type="file" id="thumbnail" name="thumbnail" class="file-input file-input-bordered file-input-sm w-full" accept="image/jpeg,image/jpg,image/png">
                        @endif
                    </div>
                    <!-- モーダル -->
                    <div id="imageModal" class="modal flex items-center justify-center">
                        <div class="modal-box flex flex-col items-center justify-center">
                            <img id="fullSizeImage" src="" alt="サムネイル" class="max-w-full h-auto mx-auto">
                            <div class="modal-action w-full flex justify-center">
                                <a href="#" class="btn btn-error w-full" onclick="closeModal()">閉じる</a>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                    function showModal(imageUrl) {
                        document.getElementById('fullSizeImage').src = imageUrl;
                        document.getElementById('imageModal').classList.add('modal-open');
                    }
                    
                    function closeModal() {
                        document.getElementById('imageModal').classList.remove('modal-open');
                    }
                    </script>
                    
                    <div class="form-group mb-4">
                        <label for="item_code" class="form-label">資材コード１３桁(JANコードor自社コード)</label>
                        <div class="flex items-center">
                            <input type="text" id="item_code" name="item_code" class="input input-bordered w-full" pattern="\d{13}" placeholder="1234567890123" value="{{ $supplyItem->item_code }}" oninput="updateLengthDisplay()" required>
                            <span id="checkmark" style="display:none; margin-left: 8px;">
                                <!-- ここにチェックマークのSVGまたはアイコンを挿入 -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            </span>
                        </div>
                        <small>現在の入力桁数: <span id="input_length">0</span>桁</small>
                    </div>
                    <script>
                        function updateLengthDisplay() {
                            var input = document.getElementById('item_code');
                            var inputLength = input.value.length;
                            var checkmark = document.getElementById('checkmark');
                        
                            document.getElementById('input_length').textContent = inputLength;
                        
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
                        <input type="text" id="item_name" name="item_name" class="input input-bordered w-full" value="{{ $supplyItem->item_name }}" placeholder="ベーカリスタ CH袋 2.5kg ロゴ無し" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="standard" class="form-label">規格</label>
                        <input type="text" id="standard" name="standard" class="input input-bordered w-full" value="{{ $supplyItem->standard }}" placeholder="280×410+27">
                    </div>

                    <div class="form-group mb-4">
                        <label for="brand_name" class="form-label">ブランド名（メーカー名）</label>
                        <input type="text" id="brand_name" name="brand_name" class="input input-bordered w-full" value="{{ $supplyItem->brand_name }}" placeholder="TP東京">
                    </div>

                    <div class="form-group mb-4">
                        <label for="category" class="form-label">分類</label>
                        <select id="category" name="category" class="select select-bordered w-full">
                            <option value="">選択してください</option>
                            <option value="製品資材" @if($supplyItem->category == '製品資材') selected @endif>製品資材</option>
                            <option value="物流資材" @if($supplyItem->category == '物流資材') selected @endif>物流資材</option>
                            <option value="業務用消耗品" @if($supplyItem->category == '業務用消耗品') selected @endif>業務用消耗品</option>
                            <option value="日用消耗品" @if($supplyItem->category == '日用消耗品') selected @endif>日用消耗品</option>
                        </select>
                    </div>
                    
                    <!-- 価格 -->
                    <div class="form-group mb-4">
                        <label for="price" class="form-label">１個単価(税抜)</label>
                        <input type="number" id="price" name="price" step="0.01" class="input input-bordered w-full" value="{{ $supplyItem->price }}" placeholder="36.5">
                    </div>

                    <!-- 発注ロット -->
                    <div class="form-group mb-4">
                        <label for="order_lot" class="form-label">発注ロット</label>
                        <input type="number" id="order_lot" name="order_lot" class="input input-bordered w-full" value="{{ $supplyItem->order_lot }}" placeholder="12000">
                    </div>

                    <!-- 備考 -->
                    <div class="form-group mb-4">
                        <label for="description" class="form-label">備考</label>
                        <textarea id="description" name="description" class="textarea textarea-bordered w-full">{{ $supplyItem->description }}</textarea>
                    </div>
                    
                </div>
                <!-- 右側のセクション -->
                <div class="lg:w-1/2">
                    
                    <!-- 資料アップロード -->
                    <div class="form-control">
                        <label class="label" for="files">資料データ（最大10個）</label>
                        @if($supplyItem->files)
                            @php
                                $files = json_decode($supplyItem->files, true);
                            @endphp
                            <div>
                                @foreach($files as $index => $fileInfo)
                                    @php
                                        // ファイルの拡張子を取得
                                        $extension = strtolower(pathinfo($fileInfo['path'], PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="relative w-full mb-2">
                                        <div class="flex items-center justify-between p-2 bg-base-100 rounded-lg">
                                            <span class="text-sm truncate">{{ $fileInfo['originalName'] }}</span>
                                            <div class="flex items-center">
                                                <a href="{{ Storage::url($fileInfo['path']) }}" download="{{ $fileInfo['originalName'] }}" class="btn btn-xs btn-primary mr-2">ダウンロード</a>
                                                <div class="btn btn-xs btn-warning"><input type="checkbox" name="delete_files[]" value="{{ $index }}" class="checkbox checkbox-warning" style="width: 1rem; height: 1rem;">削除</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @for ($i = count($files); $i < 10; $i++)
                                    <!--新規画像登録-->
                                    <div class="relative w-full mb-2">
                                        <input type="file" name="new_files[]" accept="image/*,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="file-input file-input-bordered file-input-sm w-full" />
                                    </div>
                                @endfor
                            </div>
                        @else
                            @for ($i = 0; $i < 10; $i++)
                                <div class="relative w-full mb-2">
                                    <input type="file" name="new_files[]" accept="image/*,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="file-input file-input-bordered file-input-sm w-full" />
                                </div>
                            @endfor
                        @endif
                    </div>
                    
                    <!-- 印刷ファイル -->
                    <div class="form-control">
                        <label class="label" for="files">印刷用データ（.ai /.pdfのみ）</label>
                        @if($supplyItem->print_images)
                            @php
                                $printImages = json_decode($supplyItem->print_images, true);
                            @endphp
                            <div>
                                @foreach($printImages as $index => $printInfo)
                                    @php
                                        // ファイルの拡張子を取得
                                        $extension = strtolower(pathinfo($printInfo['originalName'], PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="relative w-full mb-2">
                                        <div class="flex items-center justify-between p-2 bg-base-100 rounded-lg">
                                            <!-- ファイル名と拡張子を表示 -->
                                            <div class="flex-1 truncate">
                                                <span class="badge badge-neutral">.{{ $extension }}</span>
                                                <span class="text-sm">{{ $printInfo['originalName'] }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <a href="{{ Storage::url($printInfo['path']) }}" download="{{ $printInfo['originalName'] }}" class="btn btn-xs btn-primary mr-2">ダウンロード</a>
                                                <div class="btn btn-xs btn-warning">
                                                    <input type="checkbox" name="delete_print_images[]" value="{{ $index }}" class="checkbox checkbox-warning" style="width: 1rem; height: 1rem;">
                                                    <span class="text-xs">削除</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @for ($i = count($printImages); $i < 2; $i++)
                                    <!--新規画像登録-->
                                    <div class="relative w-full mb-2">
                                        <input type="file" name="new_print_images[]" accept=".ai,application/pdf" class="file-input file-input-bordered file-input-sm w-full" />
                                    </div>
                                @endfor
                            </div>
                        @else
                            @for ($i = 0; $i < 2; $i++)
                                <div class="relative w-full mb-2">
                                    <input type="file" name="new_print_images[]" accept=".ai,application/pdf" class="file-input file-input-bordered file-input-sm w-full" />
                                </div>
                            @endfor
                        @endif
                    </div>
                    
                    <!-- 発注点 -->
                    <div class="form-group mb-4">
                        <label for="order_point" class="form-label">発注点</label>
                        <input type="number" id="order_point" name="order_point" class="input input-bordered w-full" value="{{ $supplyItem->order_point }}" placeholder="在庫数が発注点を下回ると発注対象になります。">
                    </div>

                    <!-- 在庫定数 -->
                    <div class="form-group mb-4">
                        <label for="constant_stock" class="form-label">在庫定数</label>
                        <input type="number" id="constant_stock" name="constant_stock" class="input input-bordered w-full" value="{{ $supplyItem->constant_stock }}"placeholder="在庫定数を満たすロット数が発注されます。" min="0" max="100000" step="1">
                    </div>

                    <!-- 実在庫 -->
                    <div class="form-group mb-4">
                        <label for="actual_stock" class="form-label">実在庫</label>
                        <input type="number" id="actual_stock" name="actual_stock" class="input input-bordered w-full" value="{{ $supplyItem->actual_stock }}" placeholder="すでに実在庫があれば入力します。" min="0" max="100000" step="1">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="order_url" class="form-label">発注URL</label>
                        <input type="url" id="order_url" name="order_url" class="input input-bordered w-full" value="{{ $supplyItem->order_url }}" placeholder="http://example.com">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="order_schedule" class="form-label">次回発注予定日</label>
                        <input type="date" id="order_schedule" name="order_schedule" class="input input-bordered w-full" value="{{ $supplyItem->order_schedule ? $supplyItem->order_schedule->format('Y-m-d') : '' }}">
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="delivery_period" class="form-label">通常納期（日数）</label>
                        <input type="number" id="delivery_period" name="delivery_period" class="input input-bordered w-full" value="{{ $supplyItem->delivery_period }}" placeholder="例：3（３日）、14（2週間）、30（1ヶ月）" min="1" max="100">
                    </div>
                    
                   <div class="form-group mb-4">
                        <label for="location_code" class="form-label">保管場所</label>
                        <select id="location_code" name="location_code" class="select select-bordered w-full">
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @if($location->id == $supplyItem->location_code) selected @endif>{{ $location->location_code }}-{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label for="company_id" class="form-label">発注先</label>
                        <select id="company_id" name="company_id" class="select select-bordered w-full">
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @if($company->id == $supplyItem->company_id) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <p>最終更新者：{{ $supplyItem->user->name }} {{ $supplyItem->user->first_name }} </p>
                    </div>
                    
                </div>
            </div>
            <div class="flex mt-6">
                <!-- 更新ボタン -->
                <div class="form-control w-1/2 mr-3">
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
            <form action="{{ route('supplyItems.destroy', $supplyItem->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="w-1/2">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning w-full">資材備品情報を削除</button>
            </form>
        </div>
    </div>
</div>
@endsection
