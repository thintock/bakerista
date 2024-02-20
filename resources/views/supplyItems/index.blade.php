@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="justify-center">
        <h1 class="text-2xl font-bold mb-4">資材・備品マスタ</h1>
        <p>社内で使用される資材と備品の一覧と管理を行います。</p>
    </div>
    <div class="flex mb-4">
        <form action="{{ route('supplyItems.index') }}" method="GET" class="flex flex-wrap gap-4">
            <!--資材コードでの検索-->
            <input type="text" name="item_code" value="{{ request('item_code') }}" placeholder="資材コード(部分一致可)"  class="input input-bordered text-xs">
            <!-- ステータスでの検索 -->
            <select name="item_status" class="select select-bordered text-xs">
                <option value="">全てのステータス</option>
                <option value="未承認" {{ request('item_status') == '未承認' ? 'selected' : '' }}>未承認</option>
                <option value="承認申請中" {{ request('item_status') == '承認申請中' ? 'selected' : '' }}>承認申請中</option>
                <option value="承認済み" {{ request('item_status') == '承認済み' ? 'selected' : '' }}>承認済み</option>
                <option value="使用終了" {{ request('item_status') == '使用終了' ? 'selected' : '' }}>使用終了</option>
            </select>
            <!-- 資材備品名での検索 -->
            <input type="text" name="item_name" value="{{ request('item_name') }}" placeholder="資材備品名" class="input input-bordered text-xs">
            <!-- 発注先での検索 -->
            <select name="company_id" class="select select-bordered text-xs">
                <option value="">全ての発注先</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <!-- 分類での検索 -->
            <select name="category" class="select select-bordered text-xs">
                <option value="">全ての分類</option>
                <option value="製品資材" {{ request('category') == '製品資材' ? 'selected' : '' }}>製品資材</option>
                <option value="物流資材" {{ request('category') == '物流資材' ? 'selected' : '' }}>物流資材</option>
                <option value="業務用消耗品" {{ request('category') == '業務用消耗品' ? 'selected' : '' }}>業務用消耗品</option>
                <option value="日用消耗品" {{ request('category') == '日用消耗品' ? 'selected' : '' }}>日用消耗品</option>
            </select>
            <!-- ロケーションでの検索 -->
            <select name="location_code" class="select select-bordered text-xs">
                <option value="">全てのロケーション</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->id }}" {{ request('location_code') == $location->id ? 'selected' : '' }}>{{ $location->location_code }}-{{ $location->location_name }}</option>
                @endforeach
            </select>
            <!-- 検索ボタン -->
            <button type="submit" class="btn btn-secondary text-xs">検索</button>
            <a href="{{ route('supplyItems.index') }}" class="btn btn-info text-xs">クリア</a>
            <a href="{{ route('supplyItems.create') }}" class="btn btn-primary">新規資材・備品登録</a>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-xs bg-base-100">
            <thead>
                <tr>
                    <th>コード<br>ステータス</th>
                    <th></th>
                    <th>資材備品名</th>
                    <th>規格</th>
                    <th>発注先</th>
                    <th>分類</th>
                    <th>ロケーション</th>
                    <th></th>
                </tr>
            </thead>
            @if ($supplyItems->count() > 0)
            <tbody>
                @foreach ($supplyItems as $item)
                <tr>
                    <td>{{ $item->item_code }}<br>
                    <!-- 現在のステータスに応じたバッジ表示 -->
                        @if($item->item_status === '承認申請中')
                            <span class="badge badge-secondary ml-2">承認申請中</span>
                        @elseif($item->item_status === '未承認')
                            <span class="badge badge-accent ml-2">未承認</span>
                        @elseif($item->item_status === '承認済み')
                            <span class="badge badge-primary ml-2">承認済み</span>
                        @elseif($item->item_status === '使用終了')
                            <span class="badge badge-warning ml-2">使用終了</span>
                        @endif
                    </td>
                    <td>
                        @if($item->thumbnail)
                            <img src="{{ Storage::url($item->thumbnail) }}" alt="サムネイル" class="top-0 left-0 w-8 aspect-square object-cover rounded-lg cursor-pointer" onclick="showModal('{{ Storage::url($item->thumbnail) }}')">
                        @endif
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
                    </td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->standard }}</td>
                    <td>{{ $item->company ? $item->company->name : 'N/A' }}</td>
                    <td>{{ $item->category }}</td>
                    <td>{{ $item->location ? $item->location->location_code : 'N/A' }}<br>{{ $item->location ? $item->location->location_name : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('supplyItems.edit', $item->id) }}" class="btn btn-secondary">編集</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
            @else
            <tbody>
                <tr>
                    <td colspan="11" class="text-center">登録されている備品はありません。</td>
                </tr>
            </tbody>
            @endif
        </table>
        {{ $supplyItems->links() }} {{-- ページネーションリンク --}}
    </div>
</div>
@endsection
